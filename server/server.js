let io = require('socket.io')(6001);

io.on('connection', function(socket) {
    socket.on('add-comment', function(data) {
        io.emit('comments', {
            route: data.title,
            body: data.comment
        });
    });
});

