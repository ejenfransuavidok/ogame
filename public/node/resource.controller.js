
module.exports = {

    constructor: function(socket, conn){
        var _self = this;
        _self.socket = socket;
        _self.conn = conn;
        _self.currentUser = 0;
        _self.currentPlanet = 0;
        _self.defaultCapacity = 10000;
        
        console.log('constructor');
        
        this.requester();
        
        _self.socket.on('set_current_planet_user', function(data){
            _self.set_current_planet_user(data.currentPlanet, data.currentUser);
        });
    },
    
    requester: function(){
        var _self = this;
        if(_self.currentUser == 0 || _self.currentPlanet == 0){
            if(_self.request_current_timer)
                clearInterval(_self.request_current_timer);
            /**
             * @запрос данных currentUser, currentPlanet
             */
            _self.request_current_timer = setInterval(function(){
                _self.socket.emit('get_current_planet_user', {
                    message: '...'
                });
            }, 15000);
        }
    },
    
    set_current_planet_user: function(currentPlanet, currentUser){
        var _self = this;
        _self.currentPlanet = currentPlanet;
        _self.currentUser = currentUser;
        if(_self.currentUser != 0 && _self.currentPlanet != 0){
            if(_self.request_current_timer){
                clearInterval(_self.request_current_timer);
                console.log('currentUser = ' + _self.currentUser);
                console.log('currentPlanet = ' + _self.currentPlanet);
                if(_self.resources_synchro_timer)
                    clearInterval(_self.resources_synchro_timer);
                _self.resources_synchro_timer = setInterval(function(){
                    _self.resources_synchro();
                    //var value = _self.get_setting('PLANET_HEAVYGAS_CAPACITY');
                    //console.log(value);
                    //var metall_capacity = _self.getMetallCapacity();
                    //console.log(metall_capacity);
                }, 5000);
            }
        }
    },
    
    resources_synchro: function() {
        var _self = this;
        try{
            _self.conn.query('SELECT * FROM planets WHERE planets.id = ' + _self.currentPlanet + ' AND planets.owner = ' + _self.currentUser,function(err,rows){
                if(rows[0] != undefined){
                    rows[0]['metall_capacity']     = _self.getMetallCapacity();
                    rows[0]['heavygas_capacity']   = _self.getHeavygasCapacity();
                    rows[0]['ore_capacity']        = _self.getOreCapacity();
                    rows[0]['hydro_capacity']      = _self.getHydroCapacity();
                    rows[0]['titan_capacity']      = _self.getTitanCapacity();
                    rows[0]['darkmatter_capacity'] = _self.getDarkmatterCapacity();
                    rows[0]['redmatter_capacity']  = _self.getRedmatterCapacity();
                    //console.log(rows);
                    _self.socket.emit('get_planet_data', {
                        planet_data: JSON.stringify(rows)
                    });
                }
            });
        }
        catch(err){
            console.log(err.message);
        }
    },
    
    get_setting: function(key) {
        var _self = this;
        try{
            _self.conn.query('SELECT * FROM settings WHERE settings.setting_key = "' + key + '"', function(err, rows){
                if(err) throw err;
                _self.query_result = rows;
            });
            return _self.query_result [0];
        }
        catch(err){
            console.log(err.message);
        }
    },
    
    getMetallCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_METALL_CAPACITY').text);
            var PLANET_METALL_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.metall_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.metall_capacity += buildingType.capacity_metall * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_METALL_CAPACITY != undefined ? PLANET_METALL_CAPACITY : _self.defaultCapacity;
        }
        return _self.metall_capacity + PLANET_METALL_CAPACITY;
    },
    
    getHeavygasCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_HEAVYGAS_CAPACITY').text);
            var PLANET_HEAVYGAS_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.heavygas_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.heavygas_capacity += buildingType.capacity_heavygas * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_HEAVYGAS_CAPACITY != undefined ? PLANET_HEAVYGAS_CAPACITY : _self.defaultCapacity;
        }
        return _self.heavygas_capacity + PLANET_HEAVYGAS_CAPACITY;
    },
    
    getOreCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_ORE_CAPACITY').text);
            var PLANET_ORE_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.ore_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.ore_capacity += buildingType.capacity_ore * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_ORE_CAPACITY != undefined ? PLANET_ORE_CAPACITY : _self.defaultCapacity;
        }
        return _self.ore_capacity + PLANET_ORE_CAPACITY;
    },
    
    getHydroCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_HYDRO_CAPACITY').text);
            var PLANET_HYDRO_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.hydro_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.hydro_capacity += buildingType.capacity_hydro * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_HYDRO_CAPACITY != undefined ? PLANET_HYDRO_CAPACITY : _self.defaultCapacity;
        }
        return _self.hydro_capacity + PLANET_HYDRO_CAPACITY;
    },
    
    getTitanCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_TITAN_CAPACITY').text);
            var PLANET_TITAN_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.titan_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.titan_capacity += buildingType.capacity_titan * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_TITAN_CAPACITY != undefined ? PLANET_TITAN_CAPACITY : _self.defaultCapacity;
        }
        return _self.titan_capacity + PLANET_TITAN_CAPACITY;
    },
    
    getDarkmatterCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_DARKMATTER_CAPACITY').text);
            var PLANET_DARKMATTER_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.darkmatter_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.darkmatter_capacity += buildingType.capacity_darkmatter * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_DARKMATTER_CAPACITY != undefined ? PLANET_DARKMATTER_CAPACITY : _self.defaultCapacity;
        }
        return _self.titan_capacity + PLANET_DARKMATTER_CAPACITY;
    },
    
    getRedmatterCapacity:function ()
    {   
        try{
            
            var _self = this, capacity = parseInt(_self.get_setting('PLANET_REDMATTER_CAPACITY').text);
            var PLANET_REDMATTER_CAPACITY = capacity != undefined ? capacity : _self.defaultCapacity;
            _self.redmatter_capacity = 0;
            
            _self.conn.query('SELECT * FROM buildings WHERE buildings.planet = ' + _self.currentPlanet, function(err, buildings){
                if(buildings != undefined){
                    for(idx in buildings){
                        var building = buildings[idx];
                        _self.conn.query('SELECT * FROM building_types WHERE building_types.id = ' + building.buildingType, function(err, buildingType){
                            if(buildingType != undefined){
                                buildingType = buildingType [0];
                                _self.redmatter_capacity += buildingType.capacity_redmatter * building.level * building.level;
                            }
                        });
                    }
                }
            });
        }
        catch(err){
            console.log(err.message);
            return PLANET_REDMATTER_CAPACITY != undefined ? PLANET_REDMATTER_CAPACITY : _self.defaultCapacity;
        }
        return _self.titan_capacity + PLANET_REDMATTER_CAPACITY;
    },
    
};
