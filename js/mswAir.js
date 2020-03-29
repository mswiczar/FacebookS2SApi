function createExtID() 
{
	let now = new Date();
	let mswDummy = Math.floor((Math.random() * 12000) + 1);
	let current_datetime = new Date()
	let salida = ""
	+current_datetime.getFullYear() 
	+("0" + (current_datetime.getMonth()+1)).slice(-2)  
	+("0" + (current_datetime.getDate() )).slice(-2) 
	+("0" + (current_datetime.getHours() )).slice(-2) 
	+("0" + (current_datetime.getMinutes() )).slice(-2) 
	+("0" + (current_datetime.getSeconds() )).slice(-2) 
	+mswDummy;
	return salida;
}
function createCookie()
{
	let cookieName ="mswExtId";
	let cookieValue =createExtID();
	let daysToExpire = 365;
	let date = new Date();
	date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
	document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
	return cookieValue;
}
function getExternalId()
{
	let cookieName ="mswExtId";
	let name = cookieName + "=";
	let allCookieArray = document.cookie.split(';');
	for(var i=0; i<allCookieArray.length; i++)
	{
		var temp = allCookieArray[i].trim();
		if (temp.indexOf(name)==0)
			return temp.substring(name.length,temp.length);
	}
	return createCookie();
}