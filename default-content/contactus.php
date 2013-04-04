<h1>{{textdata.Contact Us}}</h1>
<p>We want to hear from you and when we do we will pay attention. Expect a fast follow up.</p>
{{#site}}
<div class="contact-info vcard">
	<div class="contact-icon"></div>
	<div class="contact-type">
		<b>Our Address</b><br />
		<span class="fn org">{{SolutionName}}</span><br />
		<span class='adr'>
			<span class="street-address">
			{{#Office.Address1}}{{Office.Address1}}<br/>{{/Office.Address1}}
			{{#Office.Address2}}{{Office.Address2}}<br/>{{/Office.Address2}}
			</span>
			<span class="locality">{{Office.City}}</span>, <span class="region">{{Office.State}}</span> <span class="postal-code">{{Office.PostalCode}}</span><br/>
			<span class="country">{{Office.Country}}</span>
		</span>	
	</div>
	<div class="clear2"></div>
	<div class="contact-icon"></div>
	<div class="contact-type tel">
		{{#Office.PrimaryPhone}}
		<div class="value"><span class='phonenumber-caption'><b>{{textdata.Phone}}: </b></span>{{Office.PrimaryPhone}}</div>
		{{/Office.PrimaryPhone}}
		{{#Office.TollfreeNumber}}
		<div class="value"><span class='phonenumber-caption'><b>{{textdata.Toll Free}}:&nbsp;</b></span>{{TollfreeNumber}}</div>
		{{/Office.TollfreeNumber}}
		{{#Office.FaxNumber}}
		<div class="value"><span class='phonenumber-caption'><b>{{textdata.Fax}}: </b></span>{{Office.FaxNumber}}</div>
		{{/Office.FaxNumber}}		
	</div>
	<div class="clear2"></div>
	{{#Office.Email}}
	<div class="contact-icon"></div>
	<div class="contact-type">
		<div class="email"><span class='phonenumber-caption'><b>{{textdata.Email}}: </b></span><a href="mailto:{{PrimaryEmail}}">{{Office.Email}}</a></div>	
	</div>
	<div class="clear2"></div>
	{{/Office.Email}}  
</div>
<div class="officemap">
<img src="//maps.googleapis.com/maps/api/staticmap?center={{Office.Latitude}},{{Office.Longitude}}&zoom=8&size=500x250&maptype=roadmap&markers=color:blue%7Clabel:%20%7C{{Office.Latitude}},{{Office.Longitude}}&sensor=false" />
</div>
{{/site}}