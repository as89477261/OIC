( function($) {
	$.cometchat = function() {
		var g = '';
		$("<div/>").attr("id", "cometchat_base").appendTo($("body"));
		function tooltip(a, b) {
			if ($("#cometchat_tooltip").length > 0) {
				$("#cometchat_tooltip .cometchat_tooltip_content").html(b)
			} else {
				$("body")
						.append(
								'<div id="cometchat_tooltip"><div class="cometchat_tooltip_content">' + b + '</div></div>')
			}
			var c = $('#' + a).offset();
			var d = $('#' + a).width();
			$("#cometchat_tooltip").css('top', c.top - 29).css('left',
					c.left - 47).css('display', 'block')
		}
		function chatboxKeydown(a, b, c) {
			if (a.keyCode == 13 && a.shiftKey == 0) {
				message = $(b).val();
				message = message.replace(/^\s+|\s+$/g, "");
				$(b).val('');
				$(b).css('height', '18px');
				$("#cometchat_user_" + c + "_popup .cometchat_tabcontenttext")
						.css('height', '200px');
				$(b).css('overflow-y', 'hidden');
				$(b).focus();
				if (message != '') {
					message = message.replace(/</g, "&lt;").replace(/>/g,
							"&gt;").replace(/\"/g, "&quot;");
					$(
							"#cometchat_user_" + c
									+ "_popup .cometchat_tabcontenttext")
							.append(
									'<div class="chatboxmessage"><span class="chatboxmessagefrom">Me:&nbsp;&nbsp;</span><span class="chatboxmessagecontent">' + message + '</span></div>');
					$(
							"#cometchat_user_" + c
									+ "_popup .cometchat_tabcontenttext")
							.scrollTop(
									$("#cometchat_user_"
											+ c
											+ "_popup .cometchat_tabcontenttext")[0].scrollHeight)
				}
				return false
			}
		}
		function chatboxKeyup(a, b, c) {
			var d = b.clientHeight;
			var e = 94;
			if (e > d) {
				d = Math.max(b.scrollHeight, d);
				if (e)
					d = Math.min(e, d);
				if (d > b.clientHeight) {
					$(b).css('height', d + 4 + 'px');
					$(
							"#cometchat_user_" + c
									+ "_popup .cometchat_tabcontenttext").css(
							'height', 218 - (d + 4) + 'px')
				}
			} else {
				$(b).css('overflow-y', 'auto')
			}
			$("#cometchat_user_" + c + "_popup .cometchat_tabcontenttext")
					.scrollTop(
							$("#cometchat_user_" + c
									+ "_popup .cometchat_tabcontenttext")[0].scrollHeight)
		}
		function optionsButton() {
			$("<span/>").attr("id", "cometchat_optionsbutton").addClass(
					"cometchat_tab")
					.html('<img src="images/icons/gear.png" />').appendTo(
							$("#cometchat_base"));
			$("<div/>")
					.attr("id", "cometchat_optionsbutton_popup")
					.addClass("cometchat_tabpopup")
					.css('display', 'none')
					.html(
							'<div class="cometchat_tabtitle">Comet Chat</div><div class="cometchat_tabsubtitle">Powered by Inscripts</div><div class="cometchat_tabcontent" style="background-image: url(images/tabbottomoptions.gif);padding:5px;height:156px;"><strong>My Status</strong><br/><textarea style="border:1px solid #cccccc;width:200px;padding:4px;margin-top:3px;margin-bottom:3px;font-family:\'lucida grande\',tahoma,verdana,arial,sans-serif;font-size:11px;color:#444444;overflow-x:hidden;overflow-y:auto;height:42px;"></textarea><span style="float:left"><img src="images/icons/user_available.png"></span><span style="padding-left:5px;float:left;padding-top:1px;">Available</span><span style="padding-left:7px;float:left;"><img src="images/icons/user_busy.png"></span><span style="padding-left:5px;float:left;padding-top:1px;">Busy</span><span style="padding-left:7px;float:left"><img src="images/icons/user_invisible.png"></span><span style="padding-left:5px;float:left;padding-top:1px;">Invisible</span><br clear="all"/><div style="border-top:1px solid #eeeeee;margin-top:10px;padding-top:10px;"><span><strong>Add Friend</span><input type="textbox" style="border:1px solid #cccccc;width:200px;padding:2px;margin-top:4px;font-family:\'lucida grande\',tahoma,verdana,arial,sans-serif;font-size:11px;color:#444444;font-weight:normal;"></div>')
					.appendTo($("body"));
			$('#cometchat_optionsbutton').mouseover(
					function() {
						if (!$('#cometchat_optionsbutton_popup').hasClass(
								"cometchat_tabopen")) {
							tooltip('cometchat_optionsbutton', "Chat Options")
						}
						$(this).addClass("cometchat_tabmouseover")
					});
			$('#cometchat_optionsbutton').mouseout( function() {
				$(this).removeClass("cometchat_tabmouseover");
				$("#cometchat_tooltip").css('display', 'none')
			});
			$('#cometchat_optionsbutton').click(
					function() {
						$("#cometchat_tooltip").css('display', 'none');
						$('#cometchat_optionsbutton_popup')
								.css(
										'left',
										$('#cometchat_optionsbutton')
												.position().left - 171).css(
										'bottom', '24px');
						$(this).toggleClass("cometchat_tabclick");
						$('#cometchat_optionsbutton_popup').toggleClass(
								"cometchat_tabopen");
						$('#cometchat_userstab_popup').removeClass(
								'cometchat_tabopen');
						$('#cometchat_userstab').removeClass(
								'cometchat_userstabclick').removeClass(
								'cometchat_tabclick')
					})
		}
		function createChatbox(b, c, d, e, f) {
			$.cookie('cometchat', $.cookie('cometchat') + '+' + b + '|' + c
					+ '|' + d + '|' + e);
			if ($("#cometchat_user_" + b).length > 0) {
				if (!$("#cometchat_user_" + b).hasClass('cometchat_tabclick')) {
					if (g != '') {
						$('#cometchat_user_' + g + '_popup').removeClass(
								'cometchat_tabopen');
						$('#cometchat_user_' + g).removeClass(
								'cometchat_tabclick').removeClass(
								"cometchat_usertabclick");
						g = ''
					}
					if (($("#cometchat_user_" + b).offset().left < ($(
							"#cometchat_chatboxes").offset().left + $(
							"#cometchat_chatboxes").width()))
							&& ($("#cometchat_user_" + b).offset().left - $(
									"#cometchat_chatboxes").offset().left) >= 0) {
						$("#cometchat_user_" + b).click()
					} else {
						$(".cometchat_tabalert").css('display', 'none');
						$('#cometchat_chatboxes').scrollTo(
								"#cometchat_user_" + b, 800, function() {
									$("#cometchat_user_" + b).click();
									scrollBars();
									checkPopups()
								})
					}
				}
				scrollBars();
				return
			}
			$('#cometchat_chatboxes_wide').width(
					$('#cometchat_chatboxes_wide').width() + 152);
			windowResize();
			if (c.length > 14) {
				shortname = c.substr(0, 14) + '...'
			} else {
				shortname = c
			}
			if (c.length > 24) {
				longname = c.substr(0, 24) + '...'
			} else {
				longname = c
			}
			$("<span/>").attr("id", "cometchat_user_" + b).addClass(
					"cometchat_tab").html(
					'<div style="float:left">' + shortname + '</div>')
					.appendTo($("#cometchat_chatboxes_wide"));
			$("#cometchat_user_" + b)
					.append(
							'<div class="cometchat_closebox_bottom_status cometchat_' + d + '"></div>');
			$("#cometchat_user_" + b).append(
					'<div class="cometchat_closebox_bottom"></div>');
			$("#cometchat_user_" + b + " .cometchat_closebox_bottom")
					.mouseenter( function() {
						$(this).addClass("cometchat_closebox_bottomhover")
					});
			$("#cometchat_user_" + b + " .cometchat_closebox_bottom")
					.mouseleave( function() {
						$(this).removeClass("cometchat_closebox_bottomhover")
					});
			$("#cometchat_user_" + b + " .cometchat_closebox_bottom").click(
					function() {
						$("#cometchat_user_" + b + "_popup").remove();
						$("#cometchat_user_" + b).remove();
						if (g == b) {
							g = ''
						}
						$('#cometchat_chatboxes_wide').width(
								$('#cometchat_chatboxes_wide').width() - 152);
						$('#cometchat_chatboxes').scrollTo("-=152px");
						windowResize()
					});
			$("<div/>")
					.attr("id", "cometchat_user_" + b + "_popup")
					.addClass("cometchat_tabpopup")
					.css('display', 'none')
					.html(
							'<div class="cometchat_tabtitle"><div class="name">'
									+ longname
									+ '</div></div><div class="cometchat_tabsubtitle">'
									+ e
									+ '</div><div class="cometchat_tabcontent"><div class="cometchat_tabcontenttext"></div><div class="cometchat_tabcontentinput"><textarea class="cometchat_textarea" ></textarea></div></div>')
					.appendTo($("body"));
			$("#cometchat_user_" + b + "_popup .cometchat_textarea").keydown(
					function(a) {
						return chatboxKeydown(a, this, b)
					});
			$("#cometchat_user_" + b + "_popup .cometchat_textarea").keyup(
					function(a) {
						return chatboxKeyup(a, this, b)
					});
			$("#cometchat_user_" + b + "_popup .cometchat_tabtitle").append(
					'<div class="cometchat_closebox"></div><br clear="all"/>');
			$(
					"#cometchat_user_" + b
							+ "_popup .cometchat_tabtitle .cometchat_closebox")
					.mouseenter(
							function() {
								$(this).addClass(
										"cometchat_chatboxmouseoverclose");
								$(
										"#cometchat_user_" + b
												+ "_popup .cometchat_tabtitle")
										.removeClass(
												"cometchat_chatboxtabtitlemouseover")
							});
			$(
					"#cometchat_user_" + b
							+ "_popup .cometchat_tabtitle .cometchat_closebox")
					.mouseleave(
							function() {
								$(this).removeClass(
										"cometchat_chatboxmouseoverclose");
								$(
										"#cometchat_user_" + b
												+ "_popup .cometchat_tabtitle")
										.addClass(
												"cometchat_chatboxtabtitlemouseover")
							});
			$(
					"#cometchat_user_" + b
							+ "_popup .cometchat_tabtitle .cometchat_closebox")
					.click(
							function() {
								$("#cometchat_user_" + b + "_popup").remove();
								$("#cometchat_user_" + b).remove();
								if (g == b) {
									g = ''
								}
								$('#cometchat_chatboxes_wide')
										.width(
												$('#cometchat_chatboxes_wide')
														.width() - 152);
								$('#cometchat_chatboxes').scrollTo("-=152px");
								windowResize()
							});
			$("#cometchat_user_" + b + "_popup .cometchat_tabtitle").click(
					function() {
						$("#cometchat_user_" + b).click()
					});
			$("#cometchat_user_" + b + "_popup .cometchat_tabtitle")
					.mouseenter( function() {
						$(this).addClass("cometchat_chatboxtabtitlemouseover")
					});
			$("#cometchat_user_" + b + "_popup .cometchat_tabtitle")
					.mouseleave(
							function() {
								$(this).removeClass(
										"cometchat_chatboxtabtitlemouseover")
							});
			$("#cometchat_user_" + b).mouseenter(
					function() {
						$(this).addClass("cometchat_tabmouseover");
						$("#cometchat_user_" + b + " div").addClass(
								"cometchat_tabmouseovertext")
					});
			$("#cometchat_user_" + b).mouseleave(
					function() {
						$(this).removeClass("cometchat_tabmouseover");
						$("#cometchat_user_" + b + " div").removeClass(
								"cometchat_tabmouseovertext")
					});
			$("#cometchat_user_" + b)
					.click(
							function() {
								if ($("#cometchat_user_" + b
										+ " .cometchat_tabalert").length > 0) {
									$(
											"#cometchat_user_" + b
													+ " .cometchat_tabalert")
											.remove()
								}
								if ($(this).hasClass('cometchat_tabclick')) {
									$(this).removeClass("cometchat_tabclick")
											.removeClass(
													"cometchat_usertabclick");
									$("#cometchat_user_" + b + '_popup')
											.removeClass("cometchat_tabopen");
									$(
											"#cometchat_user_"
													+ b
													+ ' .cometchat_closebox_bottom')
											.removeClass(
													"cometchat_closebox_bottom_click");
									g = ''
								} else {
									if (g != '') {
										$('#cometchat_user_' + g + '_popup')
												.removeClass(
														'cometchat_tabopen');
										$('#cometchat_user_' + g)
												.removeClass(
														'cometchat_tabclick')
												.removeClass(
														"cometchat_usertabclick");
										$(
												"#cometchat_user_"
														+ g
														+ ' .cometchat_closebox_bottom')
												.removeClass(
														"cometchat_closebox_bottom_click");
										g = ''
									}
									if (($("#cometchat_user_" + b).offset().left - $(
											"#cometchat_chatboxes").offset().left) < 0) {
										$('#cometchat_chatboxes').scrollTo(
												"#cometchat_user_" + b);
										scrollBars()
									}
									$("#cometchat_user_" + b + '_popup').css(
											'left',
											$("#cometchat_user_" + b)
													.position().left - 62).css(
											'bottom', '24px');
									$(this).addClass("cometchat_tabclick")
											.addClass("cometchat_usertabclick");
									$("#cometchat_user_" + b + '_popup')
											.addClass("cometchat_tabopen");
									$(
											"#cometchat_user_"
													+ b
													+ ' .cometchat_closebox_bottom')
											.addClass(
													"cometchat_closebox_bottom_click");
									g = b
								}
								$(
										"#cometchat_user_"
												+ b
												+ "_popup .cometchat_tabcontenttext")
										.scrollTop(
												$("#cometchat_user_"
														+ b
														+ "_popup .cometchat_tabcontenttext")[0].scrollHeight);
								$(
										"#cometchat_user_" + b
												+ "_popup .cometchat_textarea")
										.focus()
							});
			if (f != 1) {
				$("#cometchat_user_" + b).click()
			}
		}
		function addUser(a, b, c, d) {
			if (b.length > 24) {
				longname = b.substr(0, 24) + '...'
			} else {
				longname = b
			}
			$("<div />")
					.addClass(a)
					.addClass('cometchat_userlist')
					.html(
							'<span class="cometchat_userscontentname">'
									+ longname
									+ '</span><span class="cometchat_userscontentdot cometchat_'
									+ c + '"></span>')
					.appendTo(
							$('#cometchat_userstab_popup .cometchat_tabcontent .cometchat_userscontent'));
			$("#cometchat_userstab_popup .cometchat_tabcontent ." + a)
					.mouseover( function() {
						$(this).addClass('cometchat_userlist_hover')
					});
			$("#cometchat_userstab_popup .cometchat_tabcontent ." + a)
					.mouseout( function() {
						$(this).removeClass('cometchat_userlist_hover')
					});
			$("#cometchat_userstab_popup .cometchat_tabcontent ." + a).click(
					function() {
						createChatbox(a, b, c, d)
					});
			$("#cometchat_userstab_popup .cometchat_tabcontent ." + a)
					.dblclick( function() {
						createChatbox(a, b, c, d, 1)
					})
		}
		function addPopup(a, b) {
			$("#cometchat_userstab_popup .cometchat_tabcontent ." + a)
					.dblclick();
			var c = $("#cometchat_user_" + a).offset().left
					+ $("#cometchat_user_" + a).width() - 30;
			$("<span/>").css('left', c).css('top', '-5px').addClass(
					"cometchat_tabalert").html(b).appendTo(
					$("#cometchat_user_" + a))
		}
		function chatTab() {
			$("<span/>").attr("id", "cometchat_userstab").addClass(
					"cometchat_tab").html('Who\'s Online').appendTo(
					$("#cometchat_base"));
			$("<div/>")
					.attr("id", "cometchat_userstab_popup")
					.addClass("cometchat_tabpopup")
					.css('display', 'none')
					.html(
							'<div class="cometchat_tabtitle">Who\'s Online?</div><div class="cometchat_tabsubtitle">Go Offline</div><div class="cometchat_tabcontent" style="background-image: url(images/tabbottomwhosonline.gif);height:200px;padding-top:5px;padding-bottom:5px;"><div class="cometchat_userscontent"></div></div>')
					.appendTo($("body"));
			$("#cometchat_userstab_popup .cometchat_tabtitle").click(
					function() {
						$('#cometchat_userstab').click()
					});
			addUser('12345678', 'German Gordie', 'away',
					'Don\'t get too excited. I am only the wingman...');
			addUser('123456sdf79', 'Tyrone Micky', 'away', 'message');
			addUser('12345sdfsdsd67s9', 'Vernon Damion', 'busy', 'message');
			addUser('1234sdf5g679', 'Den Devin', 'away', 'message');
			addUser('1234vxcv56d79', 'Junior Terry', 'away', 'message');
			addUser('12asd345sdfb679', 'Grover Edric', 'onli', 'message');
			addUser('12aa345asddf679', 'Stan Fulton', 'offl', 'message');
			addUser('12aa345asddsdfbf679', 'Sterling Douglas', 'away',
					'message');
			$('#cometchat_userstab').mouseover( function() {
				$(this).addClass("cometchat_tabmouseover")
			});
			$('#cometchat_userstab').mouseout( function() {
				$(this).removeClass("cometchat_tabmouseover")
			});
			$('#cometchat_userstab').click(
					function() {
						$('#cometchat_userstab_popup').css('left',
								$('#cometchat_userstab').position().left + 16)
								.css('bottom', '24px');
						$(this).toggleClass("cometchat_tabclick").toggleClass(
								"cometchat_userstabclick");
						$('#cometchat_userstab_popup').toggleClass(
								"cometchat_tabopen");
						$('#cometchat_optionsbutton_popup').removeClass(
								'cometchat_tabopen');
						$('#cometchat_optionsbutton').removeClass(
								'cometchat_tabclick')
					})
		}
		function windowResize() {
			$('#cometchat_base').css('width', $(window).width() - 48);
			var a = 0;
			if (!$('#cometchat_chatbox_right').hasClass('cometchat_chatbox_lr')) {
				a = 19
			}
			if ($('#cometchat_chatboxes_wide').width() <= ($('#cometchat_base')
					.width()
					- 226 - a - 75)) {
				$('#cometchat_chatboxes').css('width',
						$('#cometchat_chatboxes_wide').width());
				$('#cometchat_chatboxes').scrollTo("0px", 0)
			} else {
				var b = $('#cometchat_chatboxes').width();
				$('#cometchat_chatboxes')
						.css(
								'width',
								Math.floor(($('#cometchat_base').width() - 226
										- a - 75) / 152) * 152);
				var c = $('#cometchat_chatboxes').width();
				if (b != c) {
					$('#cometchat_chatboxes').scrollTo("-=152px", 0)
				}
			}
			$('#cometchat_optionsbutton_popup').css('left',
					$('#cometchat_optionsbutton').position().left - 171).css(
					'bottom', '24px');
			$('#cometchat_userstab_popup').css('left',
					$('#cometchat_userstab').position().left + 16).css(
					'bottom', '24px');
			if (g != '') {
				if (($("#cometchat_user_" + g).offset().left < ($(
						"#cometchat_chatboxes").offset().left + $(
						"#cometchat_chatboxes").width()))
						&& ($("#cometchat_user_" + g).offset().left - $(
								"#cometchat_chatboxes").offset().left) >= 0) {
					$("#cometchat_user_" + g + '_popup').css('left',
							$("#cometchat_user_" + g).position().left - 62)
							.css('bottom', '24px')
				} else {
					$('#cometchat_user_' + g + '_popup').removeClass(
							'cometchat_tabopen');
					$('#cometchat_user_' + g).removeClass('cometchat_tabclick')
							.removeClass("cometchat_usertabclick");
					var d = (($("#cometchat_user_" + g).offset().left - $(
							"#cometchat_chatboxes_wide").offset().left))
							- ((Math
									.floor(($('#cometchat_chatboxes').width() / 152)) - 1) * 152);
					$('#cometchat_chatboxes').scrollTo(d, 0, function() {
						$("#cometchat_user_" + g).click()
					})
				}
			}
			checkPopups();
			scrollBars()
		}
		function checkPopups() {
			$("#cometchat_chatbox_left .cometchat_tabalertlr").html('0');
			$("#cometchat_chatbox_right .cometchat_tabalertlr").html('0');
			$("#cometchat_chatbox_left .cometchat_tabalertlr").css('display',
					'none');
			$("#cometchat_chatbox_right .cometchat_tabalertlr").css('display',
					'none');
			$(".cometchat_tabalert")
					.each(
							function() {
								if (($(this).parent().offset().left < ($(
										"#cometchat_chatboxes").offset().left + $(
										"#cometchat_chatboxes").width()))
										&& ($(this).parent().offset().left - $(
												"#cometchat_chatboxes")
												.offset().left) >= 0) {
									$(this).css('display', 'block');
									$(this).css(
											'left',
											$(this).parent().offset().left
													+ $(this).parent().width()
													- 30)
								} else {
									$(this).css('display', 'none');
									if (($(this).parent().offset().left - $(
											"#cometchat_chatboxes").offset().left) >= 0) {
										var a = $("#cometchat_chatbox_right")
												.offset().left
												+ $("#cometchat_chatbox_right")
														.width() - 30;
										$(
												"#cometchat_chatbox_right .cometchat_tabalertlr")
												.css('left', a);
										$(
												"#cometchat_chatbox_right .cometchat_tabalertlr")
												.html(
														parseInt($(
																"#cometchat_chatbox_right .cometchat_tabalertlr")
																.html())
																+ parseInt($(
																		this)
																		.html()));
										$(
												"#cometchat_chatbox_right .cometchat_tabalertlr")
												.css('display', 'block')
									} else {
										var a = $("#cometchat_chatbox_left")
												.offset().left
												+ $("#cometchat_chatbox_left")
														.width() - 22;
										$(
												"#cometchat_chatbox_left .cometchat_tabalertlr")
												.css('left', a);
										$(
												"#cometchat_chatbox_left .cometchat_tabalertlr")
												.html(
														parseInt($(
																"#cometchat_chatbox_left .cometchat_tabalertlr")
																.html())
																+ parseInt($(
																		this)
																		.html()));
										$(
												"#cometchat_chatbox_left .cometchat_tabalertlr")
												.css('display', 'block')
									}
								}
							})
		}
		function scrollBars() {
			var a = 0;
			var b = 0;
			var c = 0;
			if ($('#cometchat_chatbox_right').hasClass(
					'cometchat_chatbox_right_last')) {
				b = 1
			}
			if ($('#cometchat_chatbox_right').hasClass('cometchat_chatbox_lr')) {
				c = 1
			}
			if ($("#cometchat_chatboxes").scrollLeft() == 0) {
				$('#cometchat_chatbox_left').unbind('click', moveLeft);
				$('#cometchat_chatbox_left .cometchat_tabtext').html('0');
				$('#cometchat_chatbox_left').addClass(
						'cometchat_chatbox_left_last');
				a++
			} else {
				var d = Math
						.floor($("#cometchat_chatboxes").scrollLeft() / 152);
				$('#cometchat_chatbox_left').bind('click', moveLeft);
				$('#cometchat_chatbox_left .cometchat_tabtext').html(d);
				$('#cometchat_chatbox_left').removeClass(
						'cometchat_chatbox_left_last')
			}
			if (($("#cometchat_chatboxes").scrollLeft() + $(
					"#cometchat_chatboxes").width()) == $(
					"#cometchat_chatboxes_wide").width()) {
				$('#cometchat_chatbox_right').unbind('click', moveRight);
				$('#cometchat_chatbox_right .cometchat_tabtext').html('0');
				$('#cometchat_chatbox_right').addClass(
						'cometchat_chatbox_right_last');
				a++
			} else {
				var d = Math
						.floor(($("#cometchat_chatboxes_wide").width() - ($(
								"#cometchat_chatboxes").scrollLeft() + $(
								"#cometchat_chatboxes").width())) / 152);
				$('#cometchat_chatbox_right').bind('click', moveRight);
				$('#cometchat_chatbox_right .cometchat_tabtext').html(d);
				$('#cometchat_chatbox_right').removeClass(
						'cometchat_chatbox_right_last')
			}
			if (a == 2) {
				$('#cometchat_chatbox_right').addClass('cometchat_chatbox_lr');
				$('#cometchat_chatbox_left').addClass('cometchat_chatbox_lr')
			} else {
				$('#cometchat_chatbox_right').removeClass(
						'cometchat_chatbox_lr');
				$('#cometchat_chatbox_left')
						.removeClass('cometchat_chatbox_lr')
			}
			if ((!$('#cometchat_chatbox_right').hasClass(
					'cometchat_chatbox_right_last') && b == 1)
					|| ($('#cometchat_chatbox_right').hasClass(
							'cometchat_chatbox_right_last') && b == 0)
					|| (!$('#cometchat_chatbox_right').hasClass(
							'cometchat_chatbox_lr') && c == 1)
					|| ($('#cometchat_chatbox_right').hasClass(
							'cometchat_chatbox_lr') && c == 0)) {
				windowResize()
			}
		}
		function moveBar(a) {
			if (g != '') {
				$('#cometchat_user_' + g + '_popup').removeClass(
						'cometchat_tabopen');
				$('#cometchat_user_' + g).removeClass('cometchat_tabclick')
						.removeClass("cometchat_usertabclick")
			}
			$(".cometchat_tabalert").css('display', 'none');
			$("#cometchat_chatboxes")
					.scrollTo(
							a,
							800,
							function() {
								if (g != '') {
									if (($("#cometchat_user_" + g).offset().left < ($(
											"#cometchat_chatboxes").offset().left + $(
											"#cometchat_chatboxes").width()))
											&& ($("#cometchat_user_" + g)
													.offset().left - $(
													"#cometchat_chatboxes")
													.offset().left) >= 0) {
										$("#cometchat_user_" + g).click()
									} else {
										g = ''
									}
								}
								checkPopups();
								scrollBars()
							})
		}
		function moveLeft() {
			moveBar("-=152px")
		}
		function moveRight() {
			moveBar("+=152px")
		}
		function synchronizer() {
			var a = $.cookie('cometchat');
			var b = new Array();
			if (a) {
				b = a.split('+');
				for (chatbox in b) {
					var c = new Array();
					c = b[chatbox].split('|');
					if ($("#cometchat_user_" + c[0]).length > 0) {
					} else {
						if (c[0] && c[1]) {
						}
					}
				}
			}
			setTimeout( function() {
				synchronizer()
			}, 2000)
		}
		function initialize() {
			optionsButton();
			chatTab();
			$("<div/>").attr("id", "cometchat_chatbox_right").appendTo(
					$("#cometchat_base"));
			$("<span/>").addClass("cometchat_tabtext").appendTo(
					$("#cometchat_chatbox_right"));
			$("<span/>").css('top', '-5px').css('display', 'none').addClass(
					"cometchat_tabalertlr").appendTo(
					$("#cometchat_chatbox_right"));
			$('#cometchat_chatbox_right').bind('click', moveRight);
			$("<div/>").attr("id", "cometchat_chatboxes").appendTo(
					$("#cometchat_base"));
			$("<div/>").attr("id", "cometchat_chatboxes_wide").appendTo(
					$("#cometchat_chatboxes"));
			$("<div/>").attr("id", "cometchat_chatbox_left").appendTo(
					$("#cometchat_base"));
			$("<span/>").addClass("cometchat_tabtext").appendTo(
					$("#cometchat_chatbox_left"));
			$("<span/>").css('top', '-5px').css('display', 'none').addClass(
					"cometchat_tabalertlr").appendTo(
					$("#cometchat_chatbox_left"));
			$('#cometchat_chatbox_left').bind('click', moveLeft);
			windowResize();
			scrollBars();
			$('#cometchat_chatbox_right').mouseover( function() {
				$(this).addClass("cometchat_chatbox_lr_mouseover")
			});
			$('#cometchat_chatbox_right').mouseout( function() {
				$(this).removeClass("cometchat_chatbox_lr_mouseover")
			});
			$('#cometchat_chatbox_left').mouseover( function() {
				$(this).addClass("cometchat_chatbox_lr_mouseover")
			});
			$('#cometchat_chatbox_left').mouseout( function() {
				$(this).removeClass("cometchat_chatbox_lr_mouseover")
			});
			$(window).bind('resize', windowResize);
			if (!$.cookie('cometchat')) {
				$.cookie('cometchat', '')
			}
			synchronizer();
			$(
					"#cometchat_userstab_popup .cometchat_tabcontent ." + '12aa345asddfbfdbr679')
					.click();
			addPopup('12aa345asddf679', 9);
			$(
					"#cometchat_userstab_popup .cometchat_tabcontent ." + '12345sdf67s9')
					.click();
			addPopup('1234sdf5g679', 7);
			$(
					"#cometchat_userstab_popup .cometchat_tabcontent ." + '1234vxcv56d79')
					.click();
			$(
					"#cometchat_userstab_popup .cometchat_tabcontent ." + '12asd345sdfb679')
					.click();
			addPopup('12aa345asdfgdfbassddf679', 6)
		}
		initialize()
	}
})(jQuery);
jQuery.cookie = function(a, b, c) {
	if (typeof b != 'undefined') {
		c = c || {};
		if (b === null) {
			b = '';
			c.expires = -1
		}
		var d = '';
		if (c.expires
				&& (typeof c.expires == 'number' || c.expires.toUTCString)) {
			var e;
			if (typeof c.expires == 'number') {
				e = new Date();
				e.setTime(e.getTime() + (c.expires * 24 * 60 * 60 * 1000))
			} else {
				e = c.expires
			}
			d = '; expires=' + e.toUTCString()
		}
		var f = c.path ? '; path=' + (c.path) : '';
		var g = c.domain ? '; domain=' + (c.domain) : '';
		var h = c.secure ? '; secure' : '';
		document.cookie = [ a, '=', encodeURIComponent(b), d, f, g, h ]
				.join('')
	} else {
		var j = null;
		if (document.cookie && document.cookie != '') {
			var k = document.cookie.split(';');
			for ( var i = 0; i < k.length; i++) {
				var l = jQuery.trim(k[i]);
				if (l.substring(0, a.length + 1) == (a + '=')) {
					j = decodeURIComponent(l.substring(a.length + 1));
					break
				}
			}
		}
		return j
	}
};