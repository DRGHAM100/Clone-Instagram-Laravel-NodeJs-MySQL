var express = require('express');
var app = express();
const port = 3000;
var http = require('http').Server(app);
var io = require('socket.io')(http,{cors:{
    origin: "http://localhost:8000"
}});
var mysql = require('mysql');
var moment = require('moment');
var sockets = {};

// DB Config
var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '',
    database : 'instagram'
});

// Connect To DB
connection.connect((err)=>{
    if(err)
        throw err;
    console.log('Database Connected..');
});


io.on('connection',function(socket){

    if(!sockets[socket.handshake.query.user_id]){
        sockets[socket.handshake.query.user_id] = [];
    }

    sockets[socket.handshake.query.user_id].push(socket);

    connection.query(`UPDATE users SET is_online = 1 where id=${socket.handshake.query.user_id}`, function (err, res) {
        
        if (err) throw err;
        console.log('User Connected',socket.handshake.query.user_id);

        connection.query(`SELECT * FROM users where id=${socket.handshake.query.user_id}`,function(err,res){
            if (err) throw err;
            if(res[0]){
                socket.broadcast.emit('user_connected',res[0]);
            }
        });

    });

    
    socket.on('send_message',function(data){
        
        var group_id = (data.user_id>data.other_user_id) ? data.user_id+data.other_user_id : data.other_user_id + data.user_id;
        var time = moment().format("h:mm A");
        data.time = time;

        connection.query(`Insert into chats (user_id,other_user_id,message,group_id) 
        values (${data.user_id},${data.other_user_id},'${data.message}',${group_id})`, function (err, res) {
            if (err) throw err;
    
            data.id = res.insertId;

            for(var index in sockets[data.user_id]){
                sockets[data.user_id][index].emit('receive_message',data);
            }

            connection.query(`SELECT COUNT(id) as unread_messages From chats where
            user_id=${data.user_id} and other_user_id=${data.other_user_id} and is_read=0`,function(err,res){
                if (err) throw err;
                data.unread_messages =res[0].unread_messages;
                for(var index in sockets[data.other_user_id]){
                    sockets[data.other_user_id][index].emit('receive_message',data);
                }

            });


        });


    });
    
    
    socket.on('read_message',function(data){
        connection.query(`UPDATE chats SET is_read = 1 where id=${data}`, function (err, res) {
            if (err) throw err;
            console.log("Message Read");
        });
    })


    socket.on('user_typing',function(data){
        for(var index in sockets[data.other_user_id]){
            sockets[data.other_user_id][index].emit('user_typing',data);
        }
    });


    socket.on('disconnect',function(err){
        socket.broadcast.emit('user_disconnected',socket.handshake.query.user_id);
        for(var index in sockets[socket.handshake.query.user_id]){
            if(socket.id == sockets[socket.handshake.query.user_id][index].id){
                sockets[socket.handshake.query.user_id].splice(index,1);
            }
        }

        connection.query(`UPDATE users SET is_online = 0 where id=${socket.handshake.query.user_id}`, function (err, res) {
            if (err) throw err;
            console.log('User Disconnected',socket.handshake.query.user_id);
        });
    });

});

http.listen(port);