db.runCommand(
    {
        mapReduce: "visitedPages",
        map: function(){
            var time = this['time'];
            emit(this['domain'],
                {'count':1})
        },
        reduce: function(key, values){
            var sum = 0;
            values.forEach(function(val){
                sum+=val['count'];
            });
            return {'count':sum};
        },
        out: {'merge':'stat_domain'},
        query: {'time':{
            $gte : 1397296800,
            $lt : 1397300400}
        }
    }
);

