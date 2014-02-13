imgurcache = new Array()
		$(window).load(function(){
		var Imgur = {
			fetch: function(num) {
				var self=this;
				self.total=num;
				self.done=0;
				self.done2=0;
				self.failures=0;
				self.start=+new Date;

				$('#images').empty();
				for (var x = 0; x < num; x++) {
					self.hunt(function(id) {
						self.done++;
						self.done2++;
						self.skip=Math.floor((Math.random()*40)+25);
						$('#images').append("<a href='http://i.imgur.com/" + id + ".jpg' target='_blank' rel='noreferrer'><img src='http://i.imgur.com/" + id + "s.png' height='110' width='110' /></a>");
						if(self.done2>self.skip) {
							//$('#images').append("<iframe border=0 frameborder=0 marginheight=0 marginwidth=0 width=125 height=125 scrolling=no allowtransparency=true src=http://adserver.juicyads.com/adshow.php?adzone=222181></iframe>");
							self.done2=0;
						}
						//self.update();
					});
				}
			},
			update: function() {
				var interval=new Date-this.start;
				function speed(v) { return (~~(v/interval*1e5))/100; }
				$('#info').html((this.done<this.total?"Loading.. "+this.done+"/"+this.total+" ("+this.failures+" failures"+") ":"Done. ")+"["+speed(this.failures+this.done)+" req/s - "+speed(this.done)+" img/s]");
			},
			
			hunt: function(cb) {
				var self=this,
					id = self.random(5),
					img = new Image;
						//self.update();
						img.src = "http://i.imgur.com/"+id+"s.png";
						img.onload = function() {
							if ((img.width==198 && img.height==160) || (img.width==161 && img.height==81)) {
								// assume this is an imgur error image, and retry.
								fail();
							} else {
								cb(id);
							}
						}
						img.onerror = fail; // no escape.
				function fail() {
					self.failures++;
					//self.update();
					self.hunt(cb);
				}
			},
			random: function(len) {
				var text = new Array();
				var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				for (var i=0; i < len; i++) {
					imgurchar = possible.charAt(Math.floor(Math.random() * possible.length));
					if (text.indexOf(imgurchar) == -1) {
						text.push(imgurchar);
					} else {
						i--;
					}
				}
				text = text.join('');
				if (imgurcache.indexOf(text) == -1) {
					imgurcache.push(text);
					return text;
				} else {
					self.random(5);
					return false;
				}
			}
		};

		$('#random').bind('click', function(e) {
			Imgur.fetch(1);
		});