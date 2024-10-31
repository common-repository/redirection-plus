
/**
 * Action handle on plugin page
 */

 jQuery(document).ready( function($) {

  /**
   ** URL Redirection Part
   * Action handle when click redirection url save and changes button
   */
  $(document).on( 'click', '#rpp_redirection_url_btn', function() {        

    if ( $('#rpp_redirection_source_url').val() != '' && $('#rpp_redirection_target_url').val() != '' ) {
      var source_url       = $('#rpp_redirection_source_url').val();
      var target_url       = $('#rpp_redirection_target_url').val();
      var redirection_url_name = $('#rpp_redirection_url_name').val();
      var redirection_type = $('#rpp_redirection_type').val();

      var ajax_url = rpp_ajax_var.rpp_ajax_url;
      $.ajax({
        url:ajax_url + '?action=rpp_save_redirection_url',
        type:'POST',
        data:{
          'source_url'       : source_url,
          'target_url'       : target_url,
          'redirection_url_name' : redirection_url_name,
          'redirection_type' : redirection_type,
          'rpp_nonce'       : rpp_ajax_var.rpp_ajax_nonce
        },
        dataType: 'json',
        success:function(res){
          if( res.success == 1 ){
            $('#rpp_redirection_source_url').val('');
            $('#rpp_redirection_target_url').val('');
            $('#rpp_redirection_url_name').val('');
            location.reload();
          } else {
            alert( res.error );
            $('#rpp_redirection_source_url').val('');
            $('#rpp_redirection_target_url').val('');
            $('#rpp_redirection_url_name').val('');
            location.reload();
          }
        }
      });
    }
  });


  /**
   * Action handle When click edit action under redirection url
   */
  $(document).on( 'click', '.rpp_edit_redirection_url', function() {
    var link_id    = $(this).parent().parent().parent().find('th').eq(0).find('input').eq(0).val();
    var current_tr = $(this).parent().parent().parent();
      // Close previous open edit pane
      $('.rpp-inline-editor-for-redirection').each( function(index) {
        $(this).prev().prev().show();
        $(this).prev().remove();
        $(this).remove();
      });

      var source_url       = $(this).parent().parent().find('.rpp_edit_redirection_source_url').eq(0).val();
      var target_url       = $(this).parent().parent().parent().find('.target_url').eq(0).text();
      var redirection_url_name = $(this).parent().parent().parent().find('.redirection_url_name').eq(0).text();
      var redirection_type = $(this).parent().parent().parent().find('.redirection_type').eq(0).text();
      redirection_type = Number( redirection_type.split(' ')[0] );


      var edit_tr = 
      '<tr></tr>' + 
      '<tr id="rpp-edit-redirection-url-row" class="rpp-inline-editor-for-redirection">' +
      '<input type="hidden" value="' + link_id + '" class="hidden_redirection_link_id" />' +
      '<td class="rpp-colspan-change" colspan="8">' +
      '<div class="rpp-inline-editor-content">' +
      '<h5>Edit Redirection URL</h5>' +
      '<label>' +
      '<span class="title"> Source URL </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<input type="text" class="rpp-redirection-source-url" placeholder="Source URL" value="' + source_url + '" />' +
      '</span>' + 
      '</label>' +
      '<label>' +
      '<span class="title"> Destination URL </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<input type="text" class="rpp-redirection-target-url" placeholder="Destination URL" value="' + target_url + '" />' +
      '</span>' +
      '</label>' +
      '<label>' +
      '<span class="title"> URL Name </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<input type="text" class="rpp-redirection-url-name" placeholder="URL Name (Optional)" value="' + redirection_url_name + '" />' +
      '</span>' +
      '</label>' +
      '<label>' +
      '<span class="title"> Type </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<select type="text" class="rpp-redirection-type" >' +
      '<option value="301" ' + (redirection_type == 301? 'selected':'') + ' >301 Redirect</option>' +
      '<option value="302" ' + (redirection_type == 302? 'selected':'') + ' >302 Redirect</option>' +
      '<option value="307" ' + (redirection_type == 307? 'selected':'') + ' >307 Redirect</option>' +
      '</select>' +
      '</span>' +
      '</label>' +
      '<div class="submit rpp-inline-editor-buttons">' +
      '<input type="button" class="button-secondary cancel alignleft rpp-redirection-cancel-button" value="Cancel">' +
      '<input type="button" class="button-primary save alignright rpp-update-redirection-link-button" value="Update">' +
      '</div>' +
      '</div>' +
      '</td>' +
      '</tr>';
      current_tr.after( edit_tr );
      current_tr.hide();
    });
  // Action handle when click cancel edit button on edit redirection link pane
  $(document).on( 'click', '.rpp-redirection-cancel-button', function() {
    var adding_tr  = $(this).parent().parent().parent().parent();
    var current_tr = adding_tr.prev().prev();
    current_tr.show();
    adding_tr.prev().remove();
    adding_tr.remove();
  });
  // Action handle when click uodate link button on edit redirection link pane
  $(document).on( 'click', '.rpp-update-redirection-link-button', function() {

    var adding_tr  = $(this).parent().parent().parent().parent();
    var current_tr = adding_tr.prev().prev();

      // Origin link values
      var old_source_url       = $(this).parent().parent().parent().parent().prev().prev().find('.url').eq(0).find('.rpp_edit_redirection_source_url').eq(0).val();
      var old_target_url       = $(this).parent().parent().parent().parent().prev().prev().find('.target_url').eq(0).text();
      var old_redirection_url_name = $(this).parent().parent().parent().parent().prev().prev().find('.redirection_url_name').eq(0).text();
      var old_redirection_type = $(this).parent().parent().parent().parent().prev().prev().find('.redirection_type').eq(0).text();
      old_redirection_type     = Number( old_redirection_type.split(' ')[0] );
      // New link values
      var redirection_link_id  = $('.hidden_redirection_link_id').val();
      var source_url           = $('.rpp-redirection-source-url').val();
      var target_url           = $('.rpp-redirection-target-url').val();
      var redirection_url_name = $('.rpp-redirection-url-name').val();
      var redirection_type     = $('.rpp-redirection-type').val();


      // Compare between old values and new values
      if ( ( old_source_url != source_url ) || ( old_target_url != target_url ) || ( old_redirection_type != redirection_type ) || ( old_redirection_url_name != redirection_url_name ) ) {
        var self = this;
        var ajax_url = rpp_ajax_var.rpp_ajax_url;
        $(this).val('Waiting...');
        var send_data = {
          'redirection_link_id' : redirection_link_id,
          'source_url'          : source_url,
          'target_url'          : target_url,
          'redirection_url_name': redirection_url_name,
          'redirection_type'    : redirection_type,
          'rpp_nonce'          : rpp_ajax_var.rpp_ajax_nonce
        };

        $.ajax({
          url:ajax_url + '?action=rpp_update_redirection_link',
          type: 'POST',
          data: send_data,
          dataType: 'json',
          success: function(res){
            if( res.success == 1 ){
              location.reload();
            } else {
              alert( res.error );
              location.reload();
            }
          }
        });
      } else {
        current_tr.show();
        adding_tr.prev().remove();
        adding_tr.remove();
      }
    });

  /**
   * Action handle when click unlink action under url
   */
  $(document).on( 'click', '.rpp_trash_redirection_url', function() {
    $(this).find('a').eq(0).html('Waiting...');
    var redirection_link_id = $(this).parent().parent().parent().find('th').eq(0).find('input').eq(0).val();
    var ajax_url = rpp_ajax_var.rpp_ajax_url;
    $.ajax({
      url:ajax_url + '?action=rpp_delete_redirection_link',
      type:'POST',
      data:{
        'redirection_link_id' : redirection_link_id,
        'rpp_nonce'          : rpp_ajax_var.rpp_ajax_nonce
      },
      dataType: 'json',
      success:function(res){
        if( res.success == 1 ){
          location.reload();
        } else {
          alert( res.error );
          location.reload();
        }
      }
    });
  });


  /**
   * Clipboard button click event
   */    
  $(document).on( 'click', '.rpp_clipboard_btn', function() {
    var copyText = $(this).parent().find('input').eq(0);
    copyText.select();
    document.execCommand("copy");
  });


  /**
   ** URL Geo Redirection Part
   * Action handle when click geo redirection url save and changes button
   */
  $(document).on( 'click', '#rpp_geo_redirection_url_btn', function() {        

    if ( $('#rpp_geo_country').val() != '' && $('#rpp_geo_target_url').val() != '' && $('#rpp_geo_destination_url').val() != '' ) {
      var country          = $('#rpp_geo_country').val();
      var target_url       = $('#rpp_geo_target_url').val();
      var destination_url  = $('#rpp_geo_destination_url').val();
      var destination_type = $('#rpp_geo_destination_type').val();

      var ajax_url = rpp_ajax_var.rpp_ajax_url;
      $.ajax({
        url:ajax_url + '?action=rpp_save_geo_redirection_url',
        type:'POST',
        data:{
          'country'          : country,
          'target_url'       : target_url,
          'destination_url'  : destination_url,
          'destination_type' : destination_type,
          'rpp_nonce'       : rpp_ajax_var.rpp_ajax_nonce
        },
        dataType: 'json',
        success:function(res){
          if( res.success == 1 ){
            $('#rpp_geo_country').val('');
            $('#rpp_geo_target_url').val('');
            $('#rpp_geo_destination_url').val('');
            location.reload();
          } else {
            alert( res.error );
            $('#rpp_geo_country').val('');
            $('#rpp_geo_target_url').val('');
            $('#rpp_geo_destination_url').val('');
            location.reload();
          }
        }
      });
    }
  });

  /**
   * Action handle When click edit action under redirection url
   */
  $(document).on( 'click', '.rpp_edit_geo_redirection_url', function() {
    var link_id    = $(this).parent().parent().parent().find('th').eq(0).find('input').eq(0).val();
    var current_tr = $(this).parent().parent().parent();
      // Close previous open edit pane
      $('.rpp-inline-editor-for-geo-redirection').each( function(index) {
        $(this).prev().prev().show();
        $(this).prev().remove();
        $(this).remove();
      });

      var country          = $(this).parent().parent().find('.rpp_edit_geo_redirection_country').eq(0).attr('country_code');
      var target_url       = $(this).parent().parent().parent().find('.target_url').eq(0).find('input').eq(0).val();
      var destination_url  = $(this).parent().parent().parent().find('.destination_url').eq(0).text();
      var destination_type = $(this).parent().parent().parent().find('.destination_type').eq(0).text();
      destination_type     = Number( destination_type.split(' ')[0] );


      var edit_tr = 
      '<tr></tr>' + 
      '<tr id="rpp-edit-geo-redirection-url-row" class="rpp-inline-editor-for-geo-redirection">' +
      '<input type="hidden" value="' + link_id + '" class="hidden_geo_redirection_link_id" />' +
      '<td class="rpp-colspan-change" colspan="8">' +
      '<div class="rpp-inline-editor-content">' +
      '<h5>Edit Geo Redirection URL</h5>' +
      '<label>' +
      '<span class="title"> Country </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<select class="rpp-geo-country">' +
      '<option value="AF">Afghanistan</option>' +
      '<option value="AX">Åland Islands</option>' +
      '<option value="AL">Albania</option>' +
      '<option value="DZ">Algeria</option>' +
      '<option value="AS">American Samoa</option>' +
      '<option value="AD">Andorra</option>' +
      '<option value="AO">Angola</option>' +
      '<option value="AI">Anguilla</option>' +
      '<option value="AQ">Antarctica</option>' +
      '<option value="AG">Antigua and Barbuda</option>' +
      '<option value="AR">Argentina</option>' +
      '<option value="AM">Armenia</option>' +
      '<option value="AW">Aruba</option>' +
      '<option value="AU">Australia</option>' +
      '<option value="AT">Austria</option>' +
      '<option value="AZ">Azerbaijan</option>' +
      '<option value="BS">Bahamas</option>' +
      '<option value="BH">Bahrain</option>' +
      '<option value="BD">Bangladesh</option>' +
      '<option value="BB">Barbados</option>' +
      '<option value="BY">Belarus</option>' +
      '<option value="BE">Belgium</option>' +
      '<option value="BZ">Belize</option>' +
      '<option value="BJ">Benin</option>' +
      '<option value="BM">Bermuda</option>' +
      '<option value="BT">Bhutan</option>' +
      '<option value="BO">Bolivia, Plurinational State of</option>' +
      '<option value="BQ">Bonaire, Sint Eustatius and Saba</option>' +
      '<option value="BA">Bosnia and Herzegovina</option>' +
      '<option value="BW">Botswana</option>' +
      '<option value="BV">Bouvet Island</option>' +
      '<option value="BR">Brazil</option>' +
      '<option value="IO">British Indian Ocean Territory</option>' +
      '<option value="BN">Brunei Darussalam</option>' +
      '<option value="BG">Bulgaria</option>' +
      '<option value="BF">Burkina Faso</option>' +
      '<option value="BI">Burundi</option>' +
      '<option value="KH">Cambodia</option>' +
      '<option value="CM">Cameroon</option>' +
      '<option value="CA">Canada</option>' +
      '<option value="CV">Cape Verde</option>' +
      '<option value="KY">Cayman Islands</option>' +
      '<option value="CF">Central African Republic</option>' +
      '<option value="TD">Chad</option>' +
      '<option value="CL">Chile</option>' +
      '<option value="CN">China</option>' +
      '<option value="CX">Christmas Island</option>' +
      '<option value="CC">Cocos (Keeling) Islands</option>' +
      '<option value="CO">Colombia</option>' +
      '<option value="KM">Comoros</option>' +
      '<option value="CG">Congo</option>' +
      '<option value="CD">Congo, the Democratic Republic of the</option>' +
      '<option value="CK">Cook Islands</option>' +
      '<option value="CR">Costa Rica</option>' +
      '<option value="CI">Côte d\'Ivoire</option>' +
      '<option value="HR">Croatia</option>' +
      '<option value="CU">Cuba</option>' +
      '<option value="CW">Curaçao</option>' +
      '<option value="CY">Cyprus</option>' +
      '<option value="CZ">Czech Republic</option>' +
      '<option value="DK">Denmark</option>' +
      '<option value="DJ">Djibouti</option>' +
      '<option value="DM">Dominica</option>' +
      '<option value="DO">Dominican Republic</option>' +
      '<option value="EC">Ecuador</option>' +
      '<option value="EG">Egypt</option>' +
      '<option value="SV">El Salvador</option>' +
      '<option value="GQ">Equatorial Guinea</option>' +
      '<option value="ER">Eritrea</option>' +
      '<option value="EE">Estonia</option>' +
      '<option value="ET">Ethiopia</option>' +
      '<option value="FK">Falkland Islands (Malvinas)</option>' +
      '<option value="FO">Faroe Islands</option>' +
      '<option value="FJ">Fiji</option>' +
      '<option value="FI">Finland</option>' +
      '<option value="FR">France</option>' +
      '<option value="GF">French Guiana</option>' +
      '<option value="PF">French Polynesia</option>' +
      '<option value="TF">French Southern Territories</option>' +
      '<option value="GA">Gabon</option>' +
      '<option value="GM">Gambia</option>' +
      '<option value="GE">Georgia</option>' +
      '<option value="DE">Germany</option>' +
      '<option value="GH">Ghana</option>' +
      '<option value="GI">Gibraltar</option>' +
      '<option value="GR">Greece</option>' +
      '<option value="GL">Greenland</option>' +
      '<option value="GD">Grenada</option>' +
      '<option value="GP">Guadeloupe</option>' +
      '<option value="GU">Guam</option>' +
      '<option value="GT">Guatemala</option>' +
      '<option value="GG">Guernsey</option>' +
      '<option value="GN">Guinea</option>' +
      '<option value="GW">Guinea-Bissau</option>' +
      '<option value="GY">Guyana</option>' +
      '<option value="HT">Haiti</option>' +
      '<option value="HM">Heard Island and McDonald Islands</option>' +
      '<option value="VA">Holy See (Vatican City State)</option>' +
      '<option value="HN">Honduras</option>' +
      '<option value="HK">Hong Kong</option>' +
      '<option value="HU">Hungary</option>' +
      '<option value="IS">Iceland</option>' +
      '<option value="IN">India</option>' +
      '<option value="ID">Indonesia</option>' +
      '<option value="IR">Iran, Islamic Republic of</option>' +
      '<option value="IQ">Iraq</option>' +
      '<option value="IE">Ireland</option>' +
      '<option value="IM">Isle of Man</option>' +
      '<option value="IL">Israel</option>' +
      '<option value="IT">Italy</option>' +
      '<option value="JM">Jamaica</option>' +
      '<option value="JP">Japan</option>' +
      '<option value="JE">Jersey</option>' +
      '<option value="JO">Jordan</option>' +
      '<option value="KZ">Kazakhstan</option>' +
      '<option value="KE">Kenya</option>' +
      '<option value="KI">Kiribati</option>' +
      '<option value="KR">Korea, Republic of</option>' +
      '<option value="KW">Kuwait</option>' +
      '<option value="KG">Kyrgyzstan</option>' +
      '<option value="LA">Lao People\'s Democratic Republic</option>' +
      '<option value="LV">Latvia</option>' +
      '<option value="LB">Lebanon</option>' +
      '<option value="LS">Lesotho</option>' +
      '<option value="LR">Liberia</option>' +
      '<option value="LY">Libya</option>' +
      '<option value="LI">Liechtenstein</option>' +
      '<option value="LT">Lithuania</option>' +
      '<option value="LU">Luxembourg</option>' +
      '<option value="MO">Macao</option>' +
      '<option value="MK">Macedonia, the former Yugoslav Republic of</option>' +
      '<option value="MG">Madagascar</option>' +
      '<option value="MW">Malawi</option>' +
      '<option value="MY">Malaysia</option>' +
      '<option value="MV">Maldives</option>' +
      '<option value="ML">Mali</option>' +
      '<option value="MT">Malta</option>' +
      '<option value="MH">Marshall Islands</option>' +
      '<option value="MQ">Martinique</option>' +
      '<option value="MR">Mauritania</option>' +
      '<option value="MU">Mauritius</option>' +
      '<option value="YT">Mayotte</option>' +
      '<option value="MX">Mexico</option>' +
      '<option value="FM">Micronesia, Federated States of</option>' +
      '<option value="MD">Moldova, Republic of</option>' +
      '<option value="MC">Monaco</option>' +
      '<option value="MN">Mongolia</option>' +
      '<option value="ME">Montenegro</option>' +
      '<option value="MS">Montserrat</option>' +
      '<option value="MA">Morocco</option>' +
      '<option value="MZ">Mozambique</option>' +
      '<option value="MM">Myanmar</option>' +
      '<option value="NA">Namibia</option>' +
      '<option value="NR">Nauru</option>' +
      '<option value="NP">Nepal</option>' +
      '<option value="NL">Netherlands</option>' +
      '<option value="NC">New Caledonia</option>' +
      '<option value="NZ">New Zealand</option>' +
      '<option value="NI">Nicaragua</option>' +
      '<option value="NE">Niger</option>' +
      '<option value="NG">Nigeria</option>' +
      '<option value="NU">Niue</option>' +
      '<option value="NF">Norfolk Island</option>' +
      '<option value="MP">Northern Mariana Islands</option>' +
      '<option value="NO">Norway</option>' +
      '<option value="OM">Oman</option>' +
      '<option value="PK">Pakistan</option>' +
      '<option value="PW">Palau</option>' +
      '<option value="PS">Palestinian Territory, Occupied</option>' +
      '<option value="PA">Panama</option>' +
      '<option value="PG">Papua New Guinea</option>' +
      '<option value="PY">Paraguay</option>' +
      '<option value="PE">Peru</option>' +
      '<option value="PH">Philippines</option>' +
      '<option value="PN">Pitcairn</option>' +
      '<option value="PL">Poland</option>' +
      '<option value="PT">Portugal</option>' +
      '<option value="PR">Puerto Rico</option>' +
      '<option value="QA">Qatar</option>' +
      '<option value="RE">Réunion</option>' +
      '<option value="RO">Romania</option>' +
      '<option value="RU">Russian Federation</option>' +
      '<option value="RW">Rwanda</option>' +
      '<option value="BL">Saint Barthélemy</option>' +
      '<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>' +
      '<option value="KN">Saint Kitts and Nevis</option>' +
      '<option value="LC">Saint Lucia</option>' +
      '<option value="MF">Saint Martin (French part)</option>' +
      '<option value="PM">Saint Pierre and Miquelon</option>' +
      '<option value="VC">Saint Vincent and the Grenadines</option>' +
      '<option value="WS">Samoa</option>' +
      '<option value="SM">San Marino</option>' +
      '<option value="ST">Sao Tome and Principe</option>' +
      '<option value="SA">Saudi Arabia</option>' +
      '<option value="SN">Senegal</option>' +
      '<option value="RS">Serbia</option>' +
      '<option value="SC">Seychelles</option>' +
      '<option value="SL">Sierra Leone</option>' +
      '<option value="SG">Singapore</option>' +
      '<option value="SX">Sint Maarten (Dutch part)</option>' +
      '<option value="SK">Slovakia</option>' +
      '<option value="SI">Slovenia</option>' +
      '<option value="SB">Solomon Islands</option>' +
      '<option value="SO">Somalia</option>' +
      '<option value="ZA">South Africa</option>' +
      '<option value="GS">South Georgia and the South Sandwich Islands</option>' +
      '<option value="SS">South Sudan</option>' +
      '<option value="ES">Spain</option>' +
      '<option value="LK">Sri Lanka</option>' +
      '<option value="SD">Sudan</option>' +
      '<option value="SR">Suriname</option>' +
      '<option value="SJ">Svalbard and Jan Mayen</option>' +
      '<option value="SZ">Swaziland</option>' +
      '<option value="SE">Sweden</option>' +
      '<option value="CH">Switzerland</option>' +
      '<option value="SY">Syrian Arab Republic</option>' +
      '<option value="TW">Taiwan, Province of China</option>' +
      '<option value="TJ">Tajikistan</option>' +
      '<option value="TZ">Tanzania, United Republic of</option>' +
      '<option value="TH">Thailand</option>' +
      '<option value="TL">Timor-Leste</option>' +
      '<option value="TG">Togo</option>' +
      '<option value="TK">Tokelau</option>' +
      '<option value="TO">Tonga</option>' +
      '<option value="TT">Trinidad and Tobago</option>' +
      '<option value="TN">Tunisia</option>' +
      '<option value="TR">Turkey</option>' +
      '<option value="TM">Turkmenistan</option>' +
      '<option value="TC">Turks and Caicos Islands</option>' +
      '<option value="TV">Tuvalu</option>' +
      '<option value="UG">Uganda</option>' +
      '<option value="UA">Ukraine</option>' +
      '<option value="AE">United Arab Emirates</option>' +
      '<option value="GB">United Kingdom</option>' +
      '<option value="US">United States</option>' +
      '<option value="UM">United States Minor Outlying Islands</option>' +
      '<option value="UY">Uruguay</option>' +
      '<option value="UZ">Uzbekistan</option>' +
      '<option value="VU">Vanuatu</option>' +
      '<option value="VE">Venezuela, Bolivarian Republic of</option>' +
      '<option value="VN">Viet Nam</option>' +
      '<option value="VG">Virgin Islands, British</option>' +
      '<option value="VI">Virgin Islands, U.S.</option>' +
      '<option value="WF">Wallis and Futuna</option>' +
      '<option value="EH">Western Sahara</option>' +
      '<option value="YE">Yemen</option>' +
      '<option value="ZM">Zambia</option>' +
      '<option value="ZW">Zimbabwe</option>' +
      '</select>' +
      '</span>' + 
      '</label>' +                        
      '<label>' +
      '<span class="title"> Target URL </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<input type="text" class="rpp-geo-redirection-target-url" placeholder="Target URL" value="' + target_url + '" />' +
      '</span>' +
      '</label>' +
      '<label>' +
      '<span class="title"> Destination URL </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<input type="text" class="rpp-geo-destination-url" placeholder="Destination URL" value="' + destination_url + '" />' +
      '</span>' + 
      '</label>' +
      '<label>' +
      '<span class="title"> Destination Type </span>' +
      '<span class="rpp-input-text-wrap">' + 
      '<select type="text" class="rpp-destination-type" >' +
      '<option value="301" ' + (destination_type == 301? 'selected':'') + ' >301 Redirect</option>' +
      '<option value="302" ' + (destination_type == 302? 'selected':'') + ' >302 Redirect</option>' +
      '<option value="307" ' + (destination_type == 307? 'selected':'') + ' >307 Redirect</option>' + 
      '</select>' +
      '</span>' + 
      '</label>' +
      '<div class="submit rpp-inline-editor-buttons">' +
      '<input type="button" class="button-secondary cancel alignleft rpp-geo-redirection-cancel-button" value="Cancel">' +
      '<input type="button" class="button-primary save alignright rpp-update-geo-redirection-link-button" value="Update">' +
      '</div>' +
      '</div>' +
      '</td>' +
      '</tr>';
      current_tr.after( edit_tr );
      current_tr.hide();
      $('.rpp-geo-country').val(country);

    });
  // Action handle when click cancel edit button on edit redirection link pane
  $(document).on( 'click', '.rpp-geo-redirection-cancel-button', function() {
    var adding_tr  = $(this).parent().parent().parent().parent();
    var current_tr = adding_tr.prev().prev();
    current_tr.show();
    adding_tr.prev().remove();
    adding_tr.remove();
  });
  // Action handle when click uodate link button on edit redirection link pane
  $(document).on( 'click', '.rpp-update-geo-redirection-link-button', function() {

    var adding_tr  = $(this).parent().parent().parent().parent();
    var current_tr = adding_tr.prev().prev();

      // Origin link values
      var old_country          = $(this).parent().parent().parent().parent().prev().prev().find('.country').eq(0).find('.rpp_edit_geo_redirection_country').eq(0).attr('country_code');
      var old_target_url       = $(this).parent().parent().parent().parent().prev().prev().find('.target_url').eq(0).find('input').eq(0).val();
      var old_destination_url  = $(this).parent().parent().parent().parent().prev().prev().find('.destination_url').eq(0).text();
      var old_destination_type = $(this).parent().parent().parent().parent().prev().prev().find('.destination_type').eq(0).text();
      old_destination_type     = Number( old_destination_type.split(' ')[0] );
      // New link values
      var geo_redirection_link_id  = $('.hidden_geo_redirection_link_id').val();
      var country                  = $('.rpp-geo-country').val();
      var target_url               = $('.rpp-geo-redirection-target-url').val();
      var destination_url          = $('.rpp-geo-destination-url').val();
      var destination_type         = $('.rpp-destination-type').val();


      // Compare between old values and new values
      if ( ( old_country != country ) || ( old_target_url != target_url ) || ( old_destination_type != destination_type ) || ( old_destination_url != destination_url ) ) {
        var self = this;
        var ajax_url = rpp_ajax_var.rpp_ajax_url;
        $(this).val('Waiting...');
        var send_data = {
          'geo_redirection_link_id' : geo_redirection_link_id,
          'country'          : country,
          'target_url'       : target_url,
          'destination_url'  : destination_url,
          'destination_type' : destination_type,
          'rpp_nonce'       : rpp_ajax_var.rpp_ajax_nonce
        };

        $.ajax({
          url:ajax_url + '?action=rpp_update_geo_redirection_link',
          type: 'POST',
          data: send_data,
          dataType: 'json',
          success: function(res){
            if( res.success == 1 ){
              location.reload();
            } else {
              alert( res.error );
              location.reload();
            }
          }
        });
      } else {
        current_tr.show();
        adding_tr.prev().remove();
        adding_tr.remove();
      }
    });

  /**
   * Action handle when click unlink action under url
   */
  $(document).on( 'click', '.rpp_trash_geo_redirection_url', function() {
    $(this).find('a').eq(0).html('Waiting...');
    var geo_redirection_link_id = $(this).parent().parent().parent().find('th').eq(0).find('input').eq(0).val();
    var ajax_url = rpp_ajax_var.rpp_ajax_url;
    $.ajax({
      url:ajax_url + '?action=rpp_delete_geo_redirection_link',
      type:'POST',
      data:{
        'geo_redirection_link_id' : geo_redirection_link_id,
        'rpp_nonce'          : rpp_ajax_var.rpp_ajax_nonce
      },
      dataType: 'json',
      success:function(res){
        if( res.success == 1 ){
          location.reload();
        } else {
          alert( res.error );
          location.reload();
        }
      }
    });
  }); 
});