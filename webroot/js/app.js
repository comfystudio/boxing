$(document).ready(function(e) {
    $('.tag-tip').tooltip();
	
	$('.notification-header').click(function(e) {
		var temp = $(this).attr('id');
		var temp2 = temp.split('-');
		var id = temp2[2];
		
		$('.notification-header').each(function(index, element) {
           	$(this).removeClass('color1');
			$(this).removeClass('color2');
			$(this).removeClass('color3');
			
			if((index % 2) == 1 ){
				$(this).addClass('color2');	
			}else{
				$(this).addClass('color1');	
			}
        });
		$(this).removeClass('color1');
		$(this).removeClass('color2');
		$(this).addClass('color3');
		
		$('.manager-notifcation').each(function(index, element) {
            $(this).css('display', 'none');
        });
		$('#notification-info-'+id).css('display', 'block');
    });
	
	$('#message').keydown(function(e) {
		setTimeout(function(){
			var text = $('#message').val();
	   		$('#FightAcceptFightForm #FightMessage').val(text);
	   		$('#FightRejectFightForm #FightMessage').val(text);
		},100)
    });
	
	
	/*****************HOME Landing page**********************/
	
	$('#base-showcase-botton li').click(function(e) {
        var temp = $(this).attr('id');
		var temp2 = temp.split('_')
		var id = temp2[1];
		
		$('#base-showcase-text').hide();
		$('.base-showcase img').hide();
		$('#base-showcase-botton li').each(function(index, element) {
            $(this).removeClass('active');
        });
		
		$(this).addClass('active');
		
		if (id == 'boxer'){
			$('#base-showcase-text').css('left', '0px');
			
		}
		
		if (id == 'contract'){
			$('#base-showcase-text').css('left', '-750px');
		}
		
		if (id == 'trainer'){
			$('#base-showcase-text').css('left', '-1500px');
		}
		
		if (id == 'arrange'){
			$('#base-showcase-text').css('left', '-2250px');
		}
		
		if (id == 'fight'){
			$('#base-showcase-text').css('left', '-3000px');
		}
		
		if (id == 'item'){
			$('#base-showcase-text').css('left', '-3750px');
		}
		
		//$('.base-showcase img').fadeIn(750);
		$('#img-'+id).fadeIn(750);
		$('#base-showcase-text').fadeIn(750);
    });
	
	
	/***********************ARRANGE FIGHT************************/
	$('#FightFighter2Id').change(function() {
		var id = $(this).val();
		var value = $('.fame-value[data-id='+id+']').data('value');
		
		var string = 'Recommend '+value;
		$('#FightFee').attr('placeholder', string);
	})
	
	
	/*********************Default Update Countdown****************/
	/*var date  = new Date();
	var hour  = date.getUTCHours();
	//DAYLIGHT SAVINGS OFFSET
	var hour  = hour + 1;
	
	hour = hour % 2;
	var minute  = date.getUTCMinutes();
	var second  = date.getUTCSeconds();
	
	
	var hourOffset = ('0'+(1 - hour)).slice(-2);
	var minuteOffset = ('0'+(59 - minute)).slice(-2);
	var secondOffset = ('0'+(59 - second)).slice(-2);
	
	var timerString = hourOffset+':'+minuteOffset+':'+secondOffset;
	$('#timer').html(timerString);
	var id = 0;
	timer = setInterval(function(){
		if(hourOffset == 0 && minuteOffset == 0 && secondOffset == 0){

			var timerString  = '<a href = "/managers/home"><h2 class = "red">Refresh!</h2></a>';
			$('#timer').html(timerString);
			id = 1;
			clearInterval(timer);
			
		} else if(minuteOffset == 0  && secondOffset == 0){
			hourOffset = ('0'+(hourOffset - 1)).slice(-2);
			minuteOffset = ('0'+59).slice(-2);
			secondOffset = ('0'+59).slice(-2);
		} else if(secondOffset == 0) {
			minuteOffset = ('0'+(minuteOffset - 1)).slice(-2);
			secondOffset = ('0'+59).slice(-2);
		} else {
			secondOffset = ('0'+(secondOffset - 1)).slice(-2);
		}
		if(id  == 0){
			var timerString = hourOffset+':'+minuteOffset+':'+secondOffset;
			$('#timer').html(timerString);
		}
	},1000);*/

    /*********************ARRANGE FIGHTS*******************************/
    $('#FightFighter2Id').on('change', function (e) {
        //resetting all stats to display none
        $('.boxer-stats').addClass('none');

        //showing the one we want
        $('#boxer-stats_'+this.value).removeClass('none');

        //updating the name field
        var name = $('#boxer-stats_'+this.value).attr('data-name');
        $('#boxer-stats-name').text(name+' Stats');
    });


    /******************Formatting currency inputs to look like currency*****************************/
    Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
        var n = this,
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSeparator = decSeparator == undefined ? "." : decSeparator,
            thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
            sign = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
    };

    $('.currency-format').keyup(function(el){
        //getting the value and removing the commas so we can parse it as an INT
        var string = $(this).val().replace(/,/g, "");
        var string = parseInt(string);
        //Using above function to format our int, which does not accept strings.
        var string = string.formatMoney(0, ",", ".");
        //if value is 0 then remove value, as it's annoying to always have zero there.
        if(string == '0'){
            $(this).val("");
        }else{
            $(this).val(string);
        }
    })
});
