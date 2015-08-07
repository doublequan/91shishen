var hsh = function(){

	return {

		init:function(){

			//==========Title=============
			var count = 0;
		    Piecon.setOptions({
		    	color: '#f01c1c',
				background: '#cacaca',
				shadow: '#fff',
		    	fallback: 'force',
		    });
		    var i = setInterval(function(){

		      if (++count > 100) { 
		      	Piecon.reset(); 
		      	clearInterval(i); 
		      	return false; 
		      }
		      Piecon.setProgress(count);
		    }, 100);
		    //==========Title.End================
			//End.init
		},
		home:function(){


			//End.home
		},
        
        error:function(){
            
            //End.error  
        },
	}
}();