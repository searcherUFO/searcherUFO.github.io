<html lang="en"><head>
	<meta charset="utf-8">
	<title>Simple Chat - Arkanis Development</title>
	<meta name="author" content="Stephan Soller">
	<link href="/weblog.xml" rel="alternate" title="Arkanis Development weblog" type="application/atom+xml">
	<link href="/weblog/2010-09-04-a-simple-chat-in-about-50-lines-of-code.xml" rel="alternate" title="Comments newsfeed for a simple chat in about 50 lines of code" type="application/atom+xml">
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		body > section > nav { margin: -0.5em -0.75em 0 -0.75em; padding: 0 0.5em 0.5em 0.5em; background-color: #f7f7f7; border-bottom: 1px solid #dfdfdf; }
		body > section > nav ul { overflow: hidden; margin: 0; padding: 0; list-style: none; }
		body > section > nav ul li { float: left; margin: 0 0.5em 0 0; padding: 0; }
		body > section > nav ul li a { display: block; padding: 0.25em 0.75em; }
		body > section > nav ul li a:hover, body > section > nav ul li a:focus, body > section > nav ul li a:active { color: #eee; text-decoration: none; background-color: hsla(210, 100%, 20%, 0.75); border-radius: 5px; }
		
		ul#messages { overflow: auto; height: 15em; margin: 1em 0; padding: 0 3px; list-style: none; background-color: #f7f7f7; border: 1px solid #ccc; border-radius: 5px; box-shadow: inset 0 0 5px #ccc; }
		ul#messages li { margin: 0.35em 0; padding: 0; }
		ul#messages li small { display: block; font-size: 0.59em; color: gray; }
		ul#messages li.pending { color: #aaa; }
		
		form { font-size: 1em; margin: 1em 0; padding: 0; }
		form p { position: relative; margin: 0.5em 0; padding: 0; }
		form p input { font-size: 1em; }
		form p input#name { width: 10em; }
		form p button { position: absolute; top: 0; right: -0.5em; }
		
		ul#messages, form p, input#content { width: 40em; }
		
		pre { font-size: 0.77em; }
	</style>
	<script src="jquery-1.4.2.min.js"></script>
	<script type="text/javascript">
		// <![CDATA[
		$(document).ready(function(){
			// Remove the "loading…" list entry
			$('ul#messages > li').remove();
			
			$('form').submit(function(){
				var form = $(this);
				var name =  form.find("input[name='name']").val();
				var content =  form.find("input[name='content']").val();
				
				// Only send a new message if it's not empty (also it's ok for the server we don't need to send senseless messages)
				if (name == '' || content == '')
					return false;
				
				// Append a "pending" message (not yet confirmed from the server) as soon as the POST request is finished. The
				// text() method automatically escapes HTML so no one can harm the client.
				$.post(form.attr('action'), {'name': name, 'content': content}, function(data, status){
					$('<li class="pending" />').text(content).prepend($('<small />').text(name)).appendTo('ul#messages');
					$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
					form.find("input[name='content']").val('').focus();
				});
				return false;
			});
			
			// Poll-function that looks for new messages
			var poll_for_new_messages = function(){
				$.ajax({url: 'messages.json', dataType: 'json', ifModified: true, timeout: 2000, success: function(messages, status){
					// Skip all responses with unmodified data
					if (!messages)
						return;
					
					// Remove the pending messages from the list (they are replaced by the ones from the server later)
					$('ul#messages > li.pending').remove();
					
					// Get the ID of the last inserted message or start with -1 (so the first message from the server with 0 will
					// automatically be shown).
					var last_message_id = $('ul#messages').data('last_message_id');
					if (last_message_id == null)
						last_message_id = -1;
					
					// Add a list entry for every incomming message, but only if we not already inserted it (hence the check for
					// the newer ID than the last inserted message).
					for(var i = 0; i < messages.length; i++)
					{
						var msg = messages[i];
						if (msg.id > last_message_id)
						{
							var date = new Date(msg.time * 1000);
							$('<li/>').text(msg.content).
								prepend( $('<small />').text(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' ' + msg.name) ).
								appendTo('ul#messages');
							$('ul#messages').data('last_message_id', msg.id);
						}
					}
					
					// Remove all but the last 50 messages in the list to prevent browser slowdown with extremely large lists
					// and finally scroll down to the newes message.
					$('ul#messages > li').slice(0, -50).remove();
					$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
				}});
			};
			
			// Kick of the poll function and repeat it every two seconds
			poll_for_new_messages();
			setInterval(poll_for_new_messages, 2000);
		});
		// ]]>
	</script>
</head>
<body>

<header>
	<h1><a href="/">Arkanis Development</a></h1>
	<ul>
		<li><a href="/projects">Projects</a></li>
		<li><a href="/projects/simple-chat/">Simple Chat</a></li>
	</ul>
</header>

<section>
	<header>
		<h2>Simple Chat</h2>
		<p>With about 20 lines of PHP and about 40 lines of JavaScript.</p>
		<p>By Stephan Soller &lt;<a href="mailto:stephan.soller@helionweb.de">stephan.soller@helionweb.de</a>&gt;, released under the <a href="#license">MIT-License</a>.</p>
	</header>
	<nav>
		<ul>
			<li><a href="#demo">Demo</a></li>
			<li><a href="example.php">Striped down example</a></li>
			<li><a href="#idea">The basic idea</a></li>
			<li><a href="/weblog/2010-09-05-simple-chat-the-details">The details</a></li>
			<li><a href="example.php#source">Some source code</a></li>
			<li><a href="#download">Download</a></li>
			<li><a href="#comments">Comments</a></li>
			<li><a href="#changelog">Changelog</a></li>
		</ul>
	</nav>
	
	<p>If you're searching for a small simplistic chat but don't want some Flash, Java or other heavy-weight stuff you can stop
	searching. A PHP capable webserver and three files are all you need. And no, you don't need a database.</p>
	
	<p>This code here is inteded as a base for you to do your own stuff. However if it already is exactly what you want feel free
	to just copy it.</p>
	
	<ul>
		<li>Paste the PHP and JavaScript of the <a href="example.php#source">source code</a> into your page or take
		the <code>example.php</code> file from the <a href="#download">download</a>.</li>
		<li>Include jQuery into your page (v1.4.2 is used here).</li>
		<li>Create a <code>messages.json</code> file and make sure the webserver can read and write it.</li>
	</ul>
	
	<p>That's it. <span class="smiley smile">:)</span></p>
	
	
	<h3 id="demo">Demo</h3>
	
	<p>Everyone can post messages in the chat below. Give it a try if you want to see the chat in action.</p>
	<ul id="messages">
		
	<li><small>18:40:36 Joe Biden</small>I love ice cream and trains Jack!</li><li><small>18:57:56 Joe Biden</small>women arent real</li><li><small>18:58:4 Joe Biden</small>thye dont exist</li><li><small>18:58:18 trump</small>your mother</li><li><small>18:58:27 joe biden</small>:(</li><li><small>3:2:3 Anonymous</small>jklk</li><li><small>13:16:44 Anonymous</small>hi</li><li><small>13:16:54 Anonymous</small>hi</li><li><small>18:51:19 Negar</small>Racist chat</li><li><small>19:58:27 Anonymous</small>test</li></ul>
	
	<form action="/projects/simple-chat/index.php" method="post">
		<p>
			<input type="text" name="content" id="content">
		</p>
		<p>
			<label>
				Name:
				<input type="text" name="name" id="name" value="Anonymous">
			</label>
			<button type="submit">Send</button>
		</p>
	</form>
	
	<p>Take a look at the <a href="example.php#source">source code of <code>example.php</code></a> (it also
	shows the PHP code). It's a stripped down version of the chat so there are no fancy styles or documentation (except
	some comments) to distract you.</p>
	
	<h3 id="idea">The basic idea</h3>
	
	<ul>
		<li>Append new messages to a text file on the server, but only keep the last 10 or so messages. Optionally
		append the message to a chat log, too.</li>
		<li>Every client requests this text file every two seconds and displays all new messages inside it. These
		polling requests are very cheap (HTTP caching helps us).</li>
	</ul>
	
	<p>This simple design leads to a chat that doesn't need a database nor a large server or infrastructure. Just a small
	bunch of lines you can drop into your project and modify or extend as needed. It's so simple that it shouldn't be a
	problem to extend or adopt the code for your own purpose (e.g. multiple chat rooms, usage as message API, fancy
	styling, etc.).</p>
	
	<p>If you want to know more take a look at the <a href="/weblog/2010-09-05-simple-chat-the-details">detailed
	explanation of the design and implementation</a> of this chat.</p>
	
	
	<h3 id="download">Download</h3>
	
	<p>This archive contains everything you need for this chat: <a href="simple-chat-v1.0.1.zip">simple-chat-v1.0.1.zip</a>.</p>
	
	<p>It's the <a href="example.php">stripped down chat example</a> (<code>example.php</code>) as well as
	jQuery (<code>jquery-1.4.2.min.js</code>) and a shell script to create an empty file that's writeable by everyone
	(<code>setup.sh</code>, just in case…).</p>
	
	
	<h3 id="comments">Comments</h3>
	
	<p>If you have questions about this chat or want to share some thoughts or ideas, please post a comment on the
	<a href="http://arkanis.de/weblog/2010-09-04-a-simple-chat-in-about-50-lines-of-code">blog post of this project</a>.
	You can also send me a mail if you prefere that.</p>
	
	
	<h3 id="changelog">Changelog</h3>
	
	<dl>
		<dt>v1.0.1, 2010-08-08</dt>
			<dd>Fixed an XSS security hole repored by Craig Francis (injecting HTML code into the <code>action</code> attribute of the form).</dd>
		<dt>v1.0.0, 2010-08-04</dt>
			<dd>Initial release.</dd>
	</dl>
	
	<h3 id="license">The MIT License</h3>
	
	<p>Copyright (c) 2010 Stephan Soller &lt;<a href="mailto:stephan.soller@helionweb.de">stephan.soller@helionweb.de</a>&gt;.</p>
	
	<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
	(the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish,
	distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the
	following conditions:</p>
	
	<p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
	
	<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
	TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
	THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
	CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.</p>
	
</section>

<footer>
	Design, code and content from <a href="http://arkanis.de/">Arkanis Development</a>.<br>
	<a href="http://www.w3.org/TR/html5/">HTML5</a>,
	<a href="http://www.w3.org/Style/CSS/current-work#CSS3">CSS3</a> and
	<a href="http://tools.ietf.org/html/rfc4287">Atom 1.0</a>
	in action.
</footer>


</body></html>
