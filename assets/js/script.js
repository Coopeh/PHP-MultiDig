$(document).ready(function() {
	$('textarea#domains').empty();
	$('form').submit(function(event) {

		$('div.results').css({"opacity": 0, "pointer-events": "auto", "height": 0, "margin-top": 0});
		$('ul#content-list').empty;
		$('button.submit').animate().html('Loading...').attr("disabled", true).css({"background-color": "rgba(36, 60, 187, 0.48)"});
		var textarea = $.trim( $('textarea#domains').val() );

		if(!textarea) {

			$('div.results').css({"opacity": 1, "pointer-events": "auto", "height": "auto", "margin-top": "30px"});
			$('ul#content-list').html("No domains listed, please add some domains in a list...");
			$('button.submit').animate().html('Submit').attr("disabled", false).css({"background-color": "transparent)"});
			return false;

		} else {

			$('ul#content-list').empty();
			$('button.submit').animate().html('Loading...').attr("disabled", true).css({"background-color": "rgba(36, 60, 187, 0.48)"});
			var domains = $('textarea#domains').val().split('\n');
			var radio = $('input[name=radio]:checked', 'form#dig').val();

			domains.forEach(function(data){
				$.ajax({
					type: 'POST',
					url: 'process.php',
					data: {
						"radio": radio,
						"domains": data
					},
					success: function(data) {
						$('div.results').css({"opacity": 1, "pointer-events": "auto", "height": "auto", "margin-top": "30px"});
						$('ul#content-list').append(data);
					},
				}).fail(function(data) {
					console.log(data + "fail");
					$('button.submit').animate().html('Fail...').attr("disabled", false).css({"background-color": "rgba(187, 36, 36, 0.48)"});
					$('button.submit').hover().html('Try Again');
					return false;
				});
			});

			setTimeout(function() {
    			$('button.submit').animate().html('Submit').attr("disabled", false).css({"background-color": "transparent"});;
			}, 2500);
			$('button.copy-button').show();
			$('button.sort-button').show();
			return false;
		}
	});
	$("button.clear").click(function(){
		$("button.clear").animate().html('Domains cleared').attr("disabled", false).css({"background-color": "rgba(36, 60, 187, 0.48)"});
		$('textarea#domains').val("");
		$('div.results').css({"opacity": 0, "pointer-events": "auto", "height": 0, "margin-top": 0});
		$('ul#content-list').empty();
		$('button.copy-button').hide();
		$('button.sort-button').hide();
		setTimeout(function() {
			$('button.clear').animate().html('Clear').attr("disabled", false).css({"background-color": "transparent"});;
		}, 1500);
	  return false;
	});

	ZeroClipboard.config( { swfPath: "./assets/swf/ZeroClipboard.swf" } );
	var client = new ZeroClipboard( document.getElementById("copy-button") );

	client.on( "copy", function (event) {
	  var clipboard = event.clipboardData;
	  var c = document.getElementById("content");
	  var c = c.textContent || c.innerText;
	  clipboard.setData( "text/plain", c);
	  $('button.copy-button').animate().html('Copied To Clipboard...').css({"background-color": "rgba(36, 60, 187, 0.48)"});
	  setTimeout(function() {
		  $('button.copy-button').animate().html('Copy To Clipboard').css({"background-color": "transparent"});
	  }, 2500);
	});
});

function sortUnorderedList(ul, sortDescending) {
  if(typeof ul == "string")
    ul = document.getElementById("content-list");

  var lis = ul.getElementsByTagName("LI");
  var vals = [];

  for(var i = 0, l = lis.length; i < l; i++)
    vals.push(lis[i].innerHTML);

  vals.sort();

  if(sortDescending)
    vals.reverse();

  for(var i = 0, l = lis.length; i < l; i++)
    lis[i].innerHTML = vals[i];
}

window.onload = function() {
  var desc = false;
  document.getElementById("sort-button").onclick = function() {
    sortUnorderedList("content-list", desc);
    desc = !desc;
    return false;
  }
}