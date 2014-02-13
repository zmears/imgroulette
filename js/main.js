$(document).ready(function(){

	var viewedImages = [];
	var newImages    = [];


	function getRandomString(length) {
  		var iteration = 0;
  		var randomString = "";
  		var randomNumber;
  		
  		if(special == undefined){
      		var special = false;
  		}
  		
  		while(iteration < length){
    		randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;

			//Ensure we are not getting special chars
    		if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
			if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
			if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
			if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
    		
    		iteration++;
    		randomString += String.fromCharCode(randomNumber);
  		}
  		
  		return randomString;
	}

	function getNewImage() {
		var validImage = false;
		var img = $("<img />").attr('src', 'http://i.imgur.com/'+ getRandomString(5) +'.png')
			.load(function(){
				if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0
					|| (this.naturalWidth == 161 && this.naturalHeight == 81)
					) {
					return getNewImage();
				} else {
					newImages.push(this);
				}
            });
	}


	function cacheImages() {
		while (newImages.length < 5) {
			getNewImage();	
		}
	}

	function nextImage() {
		if (newImages.length < 1) {
			cacheImages();
		}

		var nextImage = newImages.shift();
		$('#image img').remove();
		$("#image").append(nextImage);
	}

	$('#next').click(function(){
		nextImage();
	});

	// nextImage();
});