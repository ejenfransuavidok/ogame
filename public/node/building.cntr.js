// building.cntr.js
// ========
module.exports = {
    
    execute: function(socket, conn){
        var _self = this;
        _self.socket = socket;
        _self.conn = conn;
        _self.currentUser = 0;
        _self.currentPlanet = 0;
        if(_self.int)
            clearInterval(_self.int);
        _self.int = setInterval(function(){
            _self.socket.emit('sourceBuildingComplete', {
                message: 'Hello! /currentUser = ' + _self.currentUser + '/currentPlanet = ' + _self.currentPlanet + '/'
            }) 
        }, 5000);
    },
    
};
