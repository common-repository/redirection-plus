<?php 
if(isset($_GET['rpp_tab'])) {
  $rpp_global_url_tab = $_GET['rpp_tab'];
} else {
  $rpp_global_url_tab = '';
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
  $rpp_global_url = "https"; 
else
  $rpp_global_url = "http"; 

// Here append the common URL characters. 
$rpp_global_url .= "://"; 

// Append the host(domain name, ip) to the URL. 
$rpp_global_url .= $_SERVER['HTTP_HOST']; 

// Append the requested resource location to the URL 
$rpp_global_url .= $_SERVER['REQUEST_URI'];

while ( substr( $rpp_global_url, -1, 1 ) == '/' ) {
  $rpp_global_url = substr( $rpp_global_url, 0, -1 );
  if( substr( $rpp_global_url, -1, 1 ) != '/' ) {
    break;
  }
}

$rpp_global_url_array = explode("&", $rpp_global_url);
$rpp_global_url = $rpp_global_url_array[0];


?>
<!-- Checker option page view -->
<input type="hidden" id="rpp_global_url_tab" value="<?php echo $rpp_global_url_tab;?>" />
<input type="hidden" id="rpp_global_url" value="<?php echo $rpp_global_url;?>" />
<div class="rpp_check_options col-md-12 row" style="padding: 20px 0px 0px 4px; ">
  <h4 class="rpp-page-title col-md-12"> Redirection Plus Options </h4>
  <div class="col-md-10" style="padding-right: 7px;">     
    <ul class="nav nav-tabs">
      <li class="nav-item" tab_name="rpp_redirection">
        <a class="nav-link <?php if( $rpp_global_url_tab == '' || $rpp_global_url_tab == 'rpp_redirection' ) { echo 'active';} else { echo '';} ?>" data-toggle="tab" href="#rpp_redirection"> Redirection </a>
      </li>
      <li class="nav-item" tab_name="rpp_geo_redirection">
        <a class="nav-link <?php if( $rpp_global_url_tab == 'rpp_geo_redirection' ) { echo 'active';} else { echo '';} ?>" data-toggle="tab" href="#rpp_geo_redirection"> Geo Redirection </a>
      </li>
    </ul>

    <div class="tab-content rpp_options_contents">

      <!-- URL Redirection Options -->
      <div id="rpp_redirection" class="tab-pane <?php if( $rpp_global_url_tab == '' || $rpp_global_url_tab == 'rpp_redirection' ) { echo 'active';} else { echo 'fade';} ?>">
        <br>
        <div>
          <table class="rpp_form_table">
            <tr valign="top">
              <th scope="row"> Redirection URL Name: </th>
              <td>
                <p>
                  <label>
                    <input type="text" id="rpp_redirection_url_name" class="regular-text ltr" placeholder="Redirection Name (Optional)" />
                  </label>
                </p>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"> Source URL: </th>
              <td>
                <p>
                  <label>
                    <input type="text" id="rpp_redirection_source_url" class="regular-text ltr" placeholder="The URL you want to redirect from" />
                  </label>
                </p>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"> Destination URL: </th>
              <td>
                <p>
                  <label>
                    <input type="text" id="rpp_redirection_target_url" class="regular-text ltr" placeholder="The Target URL you want to redirect" />
                  </label>
                </p>
              </td>
            </tr>
            
            <tr valign="top">
              <th scope="row">
                <label for="rpp_redirection_type">
                  Redirections Type: 
                </label>
              </th>
              <td>
                <p>
                  <label>
                    <select id="rpp_redirection_type">
                      <option value="301">
                        301 Redirect
                      </option>
                      <option value="302">
                        302 Redirect
                      </option>
                      <option value="307">
                        307 Redirect
                      </option>
                    </select>
                  </label>
                </p>
              </td>
            </tr>
          </table>
          <p class="submit">
            <input type="button" id="rpp_redirection_url_btn" class="button button-primary" value="Save Changes"/>
          </p>
        </div>          
      </div>

      <!-- URL Geo Redirection Options -->
      <div id="rpp_geo_redirection" class="tab-pane <?php if( $rpp_global_url_tab == 'rpp_geo_redirection' ) { echo 'active';} else { echo 'fade';} ?>">
        <br>
        <div>
          <table class="rpp_form_table">
            <tr valign="top">
              <th scope="row">
                <label for="rpp_geo_country">
                  Country: 
                </label>
              </th>
              <td>
                <p>
                  <label>
                    <select id="rpp_geo_country">
                      <option value="AF">Afghanistan</option>
                      <option value="AX">Aland Islands</option>
                      <option value="AL">Albania</option>
                      <option value="DZ">Algeria</option>
                      <option value="AS">American Samoa</option>
                      <option value="AD">Andorra</option>
                      <option value="AO">Angola</option>
                      <option value="AI">Anguilla</option>
                      <option value="AQ">Antarctica</option>
                      <option value="AG">Antigua and Barbuda</option>
                      <option value="AR">Argentina</option>
                      <option value="AM">Armenia</option>
                      <option value="AW">Aruba</option>
                      <option value="AU">Australia</option>
                      <option value="AT">Austria</option>
                      <option value="AZ">Azerbaijan</option>
                      <option value="BS">Bahamas</option>
                      <option value="BH">Bahrain</option>
                      <option value="BD">Bangladesh</option>
                      <option value="BB">Barbados</option>
                      <option value="BY">Belarus</option>
                      <option value="BE">Belgium</option>
                      <option value="BZ">Belize</option>
                      <option value="BJ">Benin</option>
                      <option value="BM">Bermuda</option>
                      <option value="BT">Bhutan</option>
                      <option value="BO">Bolivia</option>
                      <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                      <option value="BA">Bosnia and Herzegovina</option>
                      <option value="BW">Botswana</option>
                      <option value="BV">Bouvet Island</option>
                      <option value="BR">Brazil</option>
                      <option value="IO">British Indian Ocean Territory</option>
                      <option value="BN">Brunei Darussalam</option>
                      <option value="BG">Bulgaria</option>
                      <option value="BF">Burkina Faso</option>
                      <option value="BI">Burundi</option>
                      <option value="KH">Cambodia</option>
                      <option value="CM">Cameroon</option>
                      <option value="CA">Canada</option>
                      <option value="CV">Cape Verde</option>
                      <option value="KY">Cayman Islands</option>
                      <option value="CF">Central African Republic</option>
                      <option value="TD">Chad</option>
                      <option value="CL">Chile</option>
                      <option value="CN">China</option>
                      <option value="CX">Christmas Island</option>
                      <option value="CC">Cocos (Keeling) Islands</option>
                      <option value="CO">Colombia</option>
                      <option value="KM">Comoros</option>
                      <option value="CG">Congo</option>
                      <option value="CD">Congo, the Democratic Republic of the</option>
                      <option value="CK">Cook Islands</option>
                      <option value="CR">Costa Rica</option>
                      <option value="CI">Cote d'Ivoire</option>
                      <option value="HR">Croatia</option>
                      <option value="CU">Cuba</option>
                      <option value="CY">Cyprus</option>
                      <option value="CZ">Czech Republic</option>
                      <option value="DK">Denmark</option>
                      <option value="DJ">Djibouti</option>
                      <option value="DM">Dominica</option>
                      <option value="DO">Dominican Republic</option>
                      <option value="EC">Ecuador</option>
                      <option value="EG">Egypt</option>
                      <option value="SV">El Salvador</option>
                      <option value="GQ">Equatorial Guinea</option>
                      <option value="ER">Eritrea</option>
                      <option value="EE">Estonia</option>
                      <option value="ET">Ethiopia</option>
                      <option value="FK">Falkland Islands (Malvinas)</option>
                      <option value="FO">Faroe Islands</option>
                      <option value="FJ">Fiji</option>
                      <option value="FI">Finland</option>
                      <option value="FR">France</option>
                      <option value="GF">French Guiana</option>
                      <option value="PF">French Polynesia</option>
                      <option value="TF">French Southern Territories</option>
                      <option value="GA">Gabon</option>
                      <option value="GM">Gambia</option>
                      <option value="GE">Georgia</option>
                      <option value="DE">Germany</option>
                      <option value="GH">Ghana</option>
                      <option value="GI">Gibraltar</option>
                      <option value="GR">Greece</option>
                      <option value="GL">Greenland</option>
                      <option value="GD">Grenada</option>
                      <option value="GP">Guadeloupe</option>
                      <option value="GU">Guam</option>
                      <option value="GT">Guatemala</option>
                      <option value="GG">Guernsey</option>
                      <option value="GN">Guinea</option>
                      <option value="GW">Guinea-Bissau</option>
                      <option value="GY">Guyana</option>
                      <option value="HT">Haiti</option>
                      <option value="HM">Heard Island and McDonald Islands</option>
                      <option value="VA">Holy See (Vatican City State)</option>
                      <option value="HN">Honduras</option>
                      <option value="HK">Hong Kong</option>
                      <option value="HU">Hungary</option>
                      <option value="IS">Iceland</option>
                      <option value="IN">India</option>
                      <option value="ID">Indonesia</option>
                      <option value="IR">Iran, Islamic Republic of</option>
                      <option value="IQ">Iraq</option>
                      <option value="IE">Ireland</option>
                      <option value="IM">Isle of Man</option>
                      <option value="IL">Israel</option>
                      <option value="IT">Italy</option>
                      <option value="JM">Jamaica</option>
                      <option value="JP">Japan</option>
                      <option value="JE">Jersey</option>
                      <option value="JO">Jordan</option>
                      <option value="KZ">Kazakhstan</option>
                      <option value="KE">Kenya</option>
                      <option value="KI">Kiribati</option>
                      <option value="KP">Korea, Democratic People's Republic of</option>
                      <option value="KR">Korea, Republic of</option>
                      <option value="KW">Kuwait</option>
                      <option value="KG">Kyrgyzstan</option>
                      <option value="LA">Lao</option>
                      <option value="LV">Latvia</option>
                      <option value="LB">Lebanon</option>
                      <option value="LS">Lesotho</option>
                      <option value="LR">Liberia</option>
                      <option value="LY">Libya</option>
                      <option value="LI">Liechtenstein</option>
                      <option value="LT">Lithuania</option>
                      <option value="LU">Luxembourg</option>
                      <option value="MO">Macao</option>
                      <option value="MK">Macedonia</option>
                      <option value="MG">Madagascar</option>
                      <option value="MW">Malawi</option>
                      <option value="MY">Malaysia</option>
                      <option value="MV">Maldives</option>
                      <option value="ML">Mali</option>
                      <option value="MT">Malta</option>
                      <option value="MH">Marshall Islands</option>
                      <option value="MQ">Martinique</option>
                      <option value="MR">Mauritania</option>
                      <option value="MU">Mauritius</option>
                      <option value="YT">Mayotte</option>
                      <option value="MX">Mexico</option>
                      <option value="FM">Micronesia</option>
                      <option value="MD">Moldova</option>
                      <option value="MC">Monaco</option>
                      <option value="MN">Mongolia</option>
                      <option value="ME">Montenegro</option>
                      <option value="MS">Montserrat</option>
                      <option value="MA">Morocco</option>
                      <option value="MZ">Mozambique</option>
                      <option value="MM">Myanmar</option>
                      <option value="NA">Namibia</option>
                      <option value="NR">Nauru</option>
                      <option value="NP">Nepal</option>
                      <option value="NL">Netherlands</option>
                      <option value="NC">New Caledonia</option>
                      <option value="NZ">New Zealand</option>
                      <option value="NI">Nicaragua</option>
                      <option value="NE">Niger</option>
                      <option value="NG">Nigeria</option>
                      <option value="NU">Niue</option>
                      <option value="NF">Norfolk Island</option>
                      <option value="MP">Northern Mariana Islands</option>
                      <option value="NO">Norway</option>
                      <option value="OM">Oman</option>
                      <option value="PK">Pakistan</option>
                      <option value="PW">Palau</option>
                      <option value="PS">Palestinian Territory</option>
                      <option value="PA">Panama</option>
                      <option value="PG">Papua New Guinea</option>
                      <option value="PY">Paraguay</option>
                      <option value="PE">Peru</option>
                      <option value="PH">Philippines</option>
                      <option value="PN">Pitcairn</option>
                      <option value="PL">Poland</option>
                      <option value="PT">Portugal</option>
                      <option value="PR">Puerto Rico</option>
                      <option value="QA">Qatar</option>
                      <option value="RE">Reunion</option>
                      <option value="RO">Romania</option>
                      <option value="RU">Russian Federation</option>
                      <option value="RW">Rwanda</option>
                      <option value="BL">Saint Barthelemy</option>
                      <option value="SH">Saint Helena</option>
                      <option value="KN">Saint Kitts and Nevis</option>
                      <option value="LC">Saint Lucia</option>
                      <option value="MF">Saint Martin</option>
                      <option value="PM">Saint Pierre and Miquelon</option>
                      <option value="VC">Saint Vincent and the Grenadines</option>
                      <option value="WS">Samoa</option>
                      <option value="SM">San Marino</option>
                      <option value="ST">Sao Tome and Principe</option>
                      <option value="SA">Saudi Arabia</option>
                      <option value="SN">Senegal</option>
                      <option value="RS">Serbia</option>
                      <option value="SC">Seychelles</option>
                      <option value="SL">Sierra Leone</option>
                      <option value="SG">Singapore</option>
                      <option value="SK">Slovakia</option>
                      <option value="SI">Slovenia</option>
                      <option value="SB">Solomon Islands</option>
                      <option value="SO">Somalia</option>
                      <option value="ZA">South Africa</option>
                      <option value="GS">South Georgia and the South Sandwich Islands</option>
                      <option value="SS">South Sudan</option>
                      <option value="ES">Spain</option>
                      <option value="LK">Sri Lanka</option>
                      <option value="SD">Sudan</option>
                      <option value="SR">Suriname</option>
                      <option value="SJ">Svalbard and Jan Mayen</option>
                      <option value="SZ">Swaziland</option>
                      <option value="SE">Sweden</option>
                      <option value="CH">Switzerland</option>
                      <option value="SY">Syrian Arab Republic</option>
                      <option value="TW">Taiwan</option>
                      <option value="TJ">Tajikistan</option>
                      <option value="TZ">Tanzania</option>
                      <option value="TH">Thailand</option>
                      <option value="TL">Timor-Leste</option>
                      <option value="TG">Togo</option>
                      <option value="TK">Tokelau</option>
                      <option value="TO">Tonga</option>
                      <option value="TT">Trinidad and Tobago</option>
                      <option value="TN">Tunisia</option>
                      <option value="TR">Turkey</option>
                      <option value="TM">Turkmenistan</option>
                      <option value="TC">Turks and Caicos Islands</option>
                      <option value="TV">Tuvalu</option>
                      <option value="UG">Uganda</option>
                      <option value="UA">Ukraine</option>
                      <option value="AE">United Arab Emirates</option>
                      <option value="GB">United Kingdom</option>
                      <option value="US">United States</option>
                      <option value="UM">United States Minor Outlying Islands</option>
                      <option value="UY">Uruguay</option>
                      <option value="UZ">Uzbekistan</option>
                      <option value="VU">Vanuatu</option>
                      <option value="VE">Venezuela</option>
                      <option value="VN">Vietnam</option>
                      <option value="VG">Virgin Islands, British</option>
                      <option value="VI">Virgin Islands, U.S.</option>
                      <option value="WF">Wallis and Futuna</option>
                      <option value="EH">Western Sahara</option>
                      <option value="YE">Yemen</option>
                      <option value="ZM">Zambia</option>
                      <option value="ZW">Zimbabwe</option>
                    </select>
                  </label>
                </p>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"> Target Pgae/Post URL: </th>
              <td>
                <p>
                  <label>
                    <input type="text" id="rpp_geo_target_url" class="regular-text ltr" placeholder="Target Page/Post URL" />
                  </label>
                </p>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"> Destination URL: </th>
              <td>
                <p>
                  <label>
                    <input type="text" id="rpp_geo_destination_url" class="regular-text ltr" placeholder="Destination URL" />
                  </label>
                </p>
              </td>
            </tr>             
            <tr valign="top">
              <th scope="row">
                <label for="rpp_geo_destination_type">
                  Destination Type: 
                </label>
              </th>
              <td>
                <p>
                  <label>
                    <select id="rpp_geo_destination_type">
                      <option value="301">
                        301 Redirect
                      </option>
                      <option value="302">
                        302 Redirect
                      </option>
                      <option value="307">
                        307 Redirect
                      </option>
                    </select>
                  </label>
                </p>
              </td>
            </tr>
          </table>
          <p class="submit">
            <input type="button" id="rpp_geo_redirection_url_btn" class="button button-primary" value="Save Changes"/>
          </p>
        </div>          
      </div>

    </div>        
    
  </div>
  <div class="eblc_ads col-md-2" style="margin-top: 15px;">
    <a href="https://jannatqualitybacklinks.com/backlink-service/" target="_blank"><img style="width: 90%;" src="<?php echo plugins_url('img/be on google.png', EBLC_PLUGIN_FILE) ?>"/></a>
  </div>

</div>

<?php

switch ($rpp_global_url_tab) {
  case '':?>
    <div class="rpp_redirection_result">
      <?php
      rpp_redirection_links();
      ?>
    </div>
    <?php    
    break;
  case 'rpp_redirection':?>
    <div class="rpp_redirection_result">
      <?php
      rpp_redirection_links(); 
      ?>
    </div>
    <?php    
    break;
  case 'rpp_geo_redirection':?>
    <div class="rpp_geo_redirection_result">
      <?php
      rpp_geo_redirection_links(); 
      ?>
    </div>
    <?php    
    break;
}
?>

<script type="text/javascript">
  jQuery(document).ready( function($) {
    $(document).on( 'change', '.rpp-options-form input', function() {
      if( $(this).attr( 'type' ) == 'checkbox' ) {
        if( $(this).val() == '1'){
          $(this).parent().children().eq(0).val('0');
          $(this).val('0');
        } else {
          $(this).parent().children().eq(0).val('1');
          $(this).val('1');
        }
      }
    });

    $(document).on( 'click', '.rpp_check_options .nav-item', function() {
      location.href = $('#rpp_global_url').val() + '&rpp_tab=' + $(this).attr('tab_name');  
    });

  });       
</script> 
