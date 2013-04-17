<div class="row-fluid">
  <div class="span8 module shadow contact-form">
    <div class="pd2">
      <div id="bapi-inquiryform" class="bapi-inquiryform" data-templatename="tmpl-leadrequestform-propertyinquiry" data-log="0"></div>      
    </div>
  </div>
  <aside class="span4 module shadow contact-right-side">
    <div class="pd2"> {{#site}}
      <h3><span class="glyphicons home"><i></i>{{textdata.Our Address}}</span></h3>
      <div class="officemap">
		<img src="//maps.googleapis.com/maps/api/staticmap?center={{Office.Latitude}},{{Office.Longitude}}&zoom=8&size=500x250&maptype=roadmap&markers=color:blue%7Clabel:%20%7C{{Office.Latitude}},{{Office.Longitude}}&sensor=false" />
        <div class="pagination-centered"><small><a href="//maps.google.com/?q={{Office.Latitude}},{{Office.Longitude}}" target="_blank">{{textdata.View Larger Map}}</a></small></div>
	  </div>
      <div class="contact-info vcard">
		{{#Office}}
        <div class="contact-type"><span class="fn org">{{SolutionName}}</span><br />
			<span class="adr"><span class="street-address">{{#Address1}}{{Address1}}<br/>{{/Address1}}{{#Address2}}{{Address2}}<br/>{{/Address2}}</span><span class="locality">{{City}}</span>,&nbsp;<span class="region">{{State}}</span><br/><span class="postal-code">{{PostalCode}}</span><br/><span class="country">{{Country}}</span></span></div>
        <hr/>
        <h3><span class="glyphicons conversation"><i></i>{{textdata.Talk to Us}}</span></h3>
        <div class="contact-type tel"> {{#PrimaryPhone}}
          <div class="value"><span class='phonenumber-caption'>{{textdata.Phone}}:&nbsp;</span>{{PrimaryPhone}}</div>
          {{/PrimaryPhone}}
          {{#TollfreeNumber}}
          <div class="value"><span class='phonenumber-caption'>{{textdata.Toll Free}}:&nbsp;</span>{{TollfreeNumber}}</div>
          {{/TollfreeNumber}}            
          {{#FaxNumber}}
          <div class="value"><span class='phonenumber-caption'>{{textdata.Fax}}:&nbsp;</span>{{FaxNumber}}</div>
          {{/FaxNumber}} </div>
        {{#Email}}
        <div class="contact-type">
          <div class="email"><span class='phonenumber-caption'>{{textdata.Email}}:&nbsp;</span><a href="mailto:{{PrimaryEmail}}">{{Email}}</a></div>
        </div>
        {{/Email}}</div>
		{{/Office}}
      {{/site}} </div>
  </aside>
</div>
