function SetNotice(text) {
	$('#messageNotice').addClass('messageNotice').css('display', 'block');
	$('#messageN').text(text);
	setTimeout("$('#messageNotice').css('display', 'none')", 3000);
}

function SetWarning(text) {
	$('#messageWarning').addClass('messageWarning').css('display', 'block');
	$('#messageW').text(text);
	setTimeout("$('#messageWarning').css('display', 'none')", 3000);
}
function htmlspecialchars(text)
{
	var chars = Array("&", "<", ">", '"', "'");
	var replacements = Array("%amp;", "%lt;", "%gt;", "%quot;", "'");
	for(var i = 0; i < chars.length; i++)
	{
		var re = new RegExp(chars[i], "gi");
		if(re.test(text))
		{
			text = text.replace(re, replacements[i]);
		}
	}
	return extHTMLSpecialChars(text);
}

function extHTMLSpecialChars(text)
{
	var chars = Array("?", /\+/g);
	var replacements = Array("%ques;", "%plus;");
	for(i = 0; i < chars.length; i++)
	{
		text = text.replace(chars[i], replacements[i]);
	}
	return text;
}