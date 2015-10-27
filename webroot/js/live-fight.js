$(document).ready(function(e) {
	var rounds = [];
	var GLOBAL = 1;
	$('.live-fight-widish').html(' ');
	var html = $('#descriptionRound-1').html();
	newRound(html);
	for(i = 1; i <= 13; i++){
		rounds[i] = $('#descriptionRound-'+i).html();
		$('#round-'+i).click(function(e) {
			GLOBAL = GLOBAL+1;
			//removing stats when round is selected
			$('.fighter1_stats').css('display', 'none');
			$('.fighter2_stats').css('display', 'none');
			
			//working out the correct id as for i variable seems unrealiable
			var temp = $(this).attr('id');
			var temp2 = temp.split('-');
			var id = temp2[1];
			
			//removing active round and adding active to correct round tab
			$('.live-fight-header ul li').removeClass('active');
			$(this).addClass('active');
			
			//updating stats with correct information based on which round is selected
			var html = $('#fighter1Round-'+id).html();
			$('.fighter1_stats').html(html);
			var html = $('#fighter2Round-'+id).html();
			$('.fighter2_stats').html(html);
			
			//call newround passes in main html text for selected round
			var html = $('#descriptionRound-'+id).html();
			newRound(html);
        });
	}
	
	function newRound(html){
		
		//clearInterval(interval);
		$('.live-fight-widish').html(' ');
		var html2 = html.split('</p>');
		var length = html2.length
		var html3 = '';
		var i = 0;
		var interval = setInterval(function(){
			if(GLOBAL >= 2){
				clearInterval(interval);
				GLOBAL = GLOBAL -1;
			}
			var test = i % 16;
			if(test == 0){
				$('.live-fight-widish').html(' ');
				html3 = '';
			}
			html3 = html3 + html2[i]+'</p>';
			$('.live-fight-widish').html(html3);
			i++;
			if(i >= length) {
				$('.fighter1_stats').css('display', 'block');
				$('.fighter2_stats').css('display', 'block');
				clearInterval(interval);
				GLOBAL = GLOBAL -1;
			}
		}, 1000);
	}
});