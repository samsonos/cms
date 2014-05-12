/** JS SamsonCMS Select field interaction */
s('.__inputfield.__select').pageInit( function( fields )
{
	// Create Select field instance with save handler
	var SelectFields = new SamsonCMS_InputField( fields, function( responce, field, sp )
	{
		sp.html( s('option:selected',field).html() );
	});
});