<?php
$user= new User();
if(!$user->isLoggedIn()){
	Redirect::to('/');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, []);
		if($validate->passed()){
			$mfa = json_decode($user->data()->mfa, true);
			$mfa["enable"] = (Input::get('enable-mfa') == "on")? 1:0;
			$mfa["type"] = escape(Input::get('mfaType'));
			$mfa["semail"] = escape(Input::get('carrier'));
			$mfa = json_encode($mfa);
			try{
				$user->update(['mfa'=>$mfa]);
				Session::flash('success', '<div class="alert alert-success">Settings are saved!</div>');
				Redirect::to('');
			}catch(Exception $e){

			}
		}
	}
}

$provider = [
	'{"Provider": "Select your provider", "Email":"NULL"}', 
	'{ "Provider": "3 River Wireless", "Email": "sms.3rivers.net" }', 
	'{ "Provider": "ACS Wireless", "Email": "paging.acswireless.com" }', 
	'{ "Provider": "Advantage Communications", "Email": "advantagepaging.com" }', 
	'{ "Provider": "Airtel Wireless (Montana, USA)", "Email": "sms.airtelmontana.com" }', 
	'{ "Provider": "AirVoice", "Email": "mmode.com" }', 
	'{ "Provider": "Airtouch Pagers", "Email": "airtouch.net" }', 
	'{ "Provider": "Airtouch Pagers", "Email": "airtouchpaging.com" }', 
	'{ "Provider": "Airtouch Pagers", "Email": "alphapage.airtouch.com" }', 
	'{ "Provider": "Airtouch Pagers", "Email": "myairmail.com" }', 
	'{ "Provider": "Alaska Communications Systems", "Email": "msg.acsalaska.com" }', 
	'{ "Provider": "AllTel", "Email": "message.alltel.com" }', 
	'{ "Provider": "Alltel PCS", "Email": "message.alltel.com" }', 
	'{ "Provider": "Alltel", "Email": "alltelmessage.com" }', 
	'{ "Provider": "AlphNow", "Email": "alphanow.net" }', 
	'{ "Provider": "American Messaging (SBC/Ameritech)", "Email": "page.americanmessaging.net" }', 
	'{ "Provider": "Ameritech Clearpath", "Email": "clearpath.acswireless.com" }', 
	'{ "Provider": "Ameritech Paging", "Email": "pageapi.com" }', 
	'{ "Provider": "Aql", "Email": "text.aql.com" }', 
	'{ "Provider": "Arch Pagers (PageNet)", "Email": "archwireless.net" }', 
	'{ "Provider": "Arch Pagers (PageNet)", "Email": "epage.arch.com" }', 
	'{ "Provider": "AT&T", "Email": "txt.att.net" }', 
	'{ "Provider": "AT&T Free2Go", "Email": "mmode.com" }', 
	'{ "Provider": "AT&T PCS", "Email": "xmobile.att.net" }', 
	'{ "Provider": "AT&T Pocketnet PCS", "Email": "xdpcs.mobile.att.net" }', 
	'{ "Provider": "Beepwear", "Email": "xbeepwear.net" }', 
	'{ "Provider": "Bell Atlantic", "Email": "xmessage.bam.com" }', 
	'{ "Provider": "Bell South (Blackberry)", "Email": "bellsouthtips.com" }', 
	'{ "Provider": "Bell South Mobility", "Email": "blsdcs.net" }', 
	'{ "Provider": "Bell South", "Email": "blsdcs.net" }', 
	'{ "Provider": "Bell South", "Email": "sms.bellsouth.com" }', 
	'{ "Provider": "Bell South", "Email": "wireless.bellsouth.com" }', 
	'{ "Provider": "Bluegrass Cellular", "Email": "sms.bluecell.com" }', 
	'{ "Provider": "Boost Mobile", "Email": "myboostmobile.com" }', 
	'{ "Provider": "Boost", "Email": "myboostmobile.com" }', 
	'{ "Provider": "CallPlus", "Email": "mmode.com" }', 
	'{ "Provider": "Carolina Mobile Communications", "Email": "cmcpaging.com" }', 
	'{ "Provider": "Carolina West Wireless", "Email": "xcwwsms.com" }', 
	'{ "Provider": "Cellular One MMS", "Email": "xmms.uscc.net" }', 
	'{ "Provider": "Cellular One East Coast", "Email": "phone.cellone.net" }', 
	'{ "Provider": "Cellular One PCS", "Email": "paging.cellone-sf.com" }', 
	'{ "Provider": "Cellular One South West", "Email": "swmsg.com" }', 
	'{ "Provider": "Cellular One West", "Email": "mycellone.com" }', 
	'{ "Provider": "Cellular One", "Email": "xcellularone.txtmsg.com" }', 
	'{ "Provider": "Cellular One", "Email": "xcellularone.textmsg.com" }', 
	'{ "Provider": "Cellular One", "Email": "xcell1.textmsg.com" }', 
	'{ "Provider": "Cellular One", "Email": "message.cellone-sf.com" }', 
	'{ "Provider": "Cellular One", "Email": "mobile.celloneusa.com" }', 
	'{ "Provider": "Cellular One", "Email": "sbcemail.com" }', 
	'{ "Provider": "Cellular South", "Email": "csouth1.com" }', 
	'{ "Provider": "Centennial Wireless", "Email": "xcwemail.com" }', 
	'{ "Provider": "Central Vermont Communications", "Email": "cvcpaging.com" }', 
	'{ "Provider": "CenturyTel", "Email": "messaging.centurytel.net" }', 
	'{ "Provider": "Cincinnati Bell Wireless", "Email": "xgocbw.com" }', 
	'{ "Provider": "Cingular", "Email": "xmycingular.com" }', 
	'{ "Provider": "Cingular", "Email": "xmycingular.net" }', 
	'{ "Provider": "Cingular", "Email": "xmms.cingularme.com" }', 
	'{ "Provider": "Cingular", "Email": "xpage.cingular.com" }', 
	'{ "Provider": "Cingular", "Email": "cingularme.com" }', 
	'{ "Provider": "Cingular (TDMA)", "Email": "mmode.com" }', 
	'{ "Provider": "Cingular Wireless", "Email": "mobile.mycingular.net" }',
	'{ "Provider": "Cingular Wireless", "Email": "xmycingular.textmsg.com" }', 
	'{ "Provider": "Cingular Wireless", "Email": "xmobile.mycingular.com" }', 
	'{ "Provider": "Comcast", "Email": "xcomcastpcs.textmsg.com" }', 
	'{ "Provider": "Communication Specialists", "Email": "xpager.comspeco.com" }', 
	'{ "Provider": "Communication Specialists", "Email": "pageme.comspeco.net" }', 
	'{ "Provider": "Cook Paging", "Email": "cookmail.com" }', 
	'{ "Provider": "Corr Wireless Communications", "Email": "corrwireless.net" }', 
	'{ "Provider": "Cricket", "Email": "xsms.mycricket.com" }', 
	'{ "Provider": "Digi-Page / Page Kansas", "Email": "xpage.hit.net" }', 
	'{ "Provider": "Dobson Communications Corporation", "Email": "mobile.dobson.net" }', 
	'{ "Provider": "Dobson-Alex Wireless / Dobson-Cellular One", "Email": "mobile.cellularone.com" }', 
	'{ "Provider": "Edge Wireless", "Email": "sms.edgewireless.com" }', 
	'{ "Provider": "Gabriel Wireless", "Email": "xepage.gabrielwireless.com" }', 
	'{ "Provider": "Galaxy Corporation", "Email": "sendabeep.net" }', 
	'{ "Provider": "GCS Paging", "Email": "webpager.us" }', 
	'{ "Provider": "General Communications Inc. (Alaska)", "Email": "xmsg.gci.net" }', 
	'{ "Provider": "Globalstar (satellite)", "Email": "xmsg.globalstarusa.com" }', 
	'{ "Provider": "GrayLink / Porta-Phone", "Email": "epage.porta-phone.com" }', 
	'{ "Provider": "GTE", "Email": "gte.pagegate.net" }', 
	'{ "Provider": "GTE", "Email": "messagealert.com" }', 
	'{ "Provider": "GTE", "Email": "xairmessage.net" }', 
	'{ "Provider": "Helio", "Email": "xmessaging.sprintpcs.com" }', 
	'{ "Provider": "Houston Cellular", "Email": "text.houstoncellular.net" }', 
	'{ "Provider": "Illinois Valley Cellular", "Email": "xivctext.com" }', 
	'{ "Provider": "Infopage Systems", "Email": "xpage.infopagesystems.com" }', 
	'{ "Provider": "Inland Cellular Telephone", "Email": "inlandlink.com" }', 
	'{ "Provider": "Iridium (satellite)", "Email": "xmsg.iridium.com" }', 
	//'{ "Provider": "i wireless", "Email": "xxxxxxxxxx.iws@iwspcs.net" }', 
	'{ "Provider": "JSM Tele-Page", "Email": "jsmtel.com" }', 
	'{ "Provider": "Lauttamus Communication", "Email": "e-page.net" }', 
	'{ "Provider": "MCI Phone", "Email": "mci.com" }', 
	'{ "Provider": "MCI", "Email": "pagemci.com" }', 
	'{ "Provider": "Metro PCS", "Email": "metropcs.sms.us" }', 
	'{ "Provider": "Metro PCS", "Email": "mymetropcs.com" }', 
	'{ "Provider": "MetroPCS", "Email": "mymetropcs.com" }', 
	'{ "Provider": "Metrocall 2-way", "Email": "my2way.com" }', 
	'{ "Provider": "Metrocall", "Email": "page.metrocall.com" }', 
	'{ "Provider": "Midwest Wireless", "Email": "clearlydigital.com" }', 
	'{ "Provider": "Mobilecom PA", "Email": "page.mobilcom.net" }', 
	'{ "Provider": "Mobilfone", "Email": "page.mobilfone.com" }', 
	'{ "Provider": "MobiPCS (Hawaii only)", "Email": "xmobipcs.net" }', 
	'{ "Provider": "Motient", "Email": "isp.com" }', 
	'{ "Provider": "Morris Wireless", "Email": "beepone.net" }', 
	'{ "Provider": "Northeast Paging", "Email": "xpager.ucom.com" }', 
	'{ "Provider": "Nextel", "Email": "messaging.nextel.com" }', 
	'{ "Provider": "Nextel", "Email": "page.nextel.com" }', 
	'{ "Provider": "NPI Wireless", "Email": "npiwireless.com" }', 
	'{ "Provider": "Ntelos", "Email": "pcs.ntelos.com" }', 
	'{ "Provider": "O2", "Email": "xmobile.celloneusa.com" }', 
	'{ "Provider": "Omnipoint", "Email": "omnipoint.com" }', 
	'{ "Provider": "Omnipoint", "Email": "omnipointpcs.com" }', 
	'{ "Provider": "OnlineBeep", "Email": "onlinebeep.net" }', 
	'{ "Provider": "PCS One", "Email": "pcsone.net" }', 
	'{ "Provider": "Pacific Bell", "Email": "pacbellpcs.net" }', 
	'{ "Provider": "PageMart Advanced /2way", "Email": "xairmessage.net" }', 
	'{ "Provider": "PageMart", "Email": "pagemart.net" }', 
	'{ "Provider": "PageOne NorthWest", "Email": "page1nw.com" }', 
	'{ "Provider": "Pioneer / Enid Cellular", "Email": "msg.pioneerenidcellular.com" }', 
	'{ "Provider": "Price Communications", "Email": "mobilecell1se.com" }', 
	'{ "Provider": "Primeco", "Email": "xemail.uscc.net" }',
	'{ "Provider": "Project Fi", "Email": "msg.fi.google.com"}', 
	'{ "Provider": "ProPage", "Email": "page.propage.net" }', 
	'{ "Provider": "Public Service Cellular", "Email": "sms.pscel.com" }', 
	'{ "Provider": "Qualcomm", "Email": "name@pager.qualcomm.com" }', 
	'{ "Provider": "Qwest", "Email": "qwestmp.com" }', 
	'{ "Provider": "RAM Page", "Email": "ram-page.com" }', 
	'{ "Provider": "Republic Wireless", "Email": "text.republicwireless.com"}', 
	'{ "Provider": "SBC Ameritech Paging (see also American Messaging)", "Email": "xpaging.acswireless.com" }', 
	'{ "Provider": "ST Paging", "Email": "pin@page.stpaging.com" }', 
	'{ "Provider": "Safaricom", "Email": "safaricomsms.com" }', 
	'{ "Provider": "Satelindo GSM", "Email": "satelindogsm.com" }', 
	'{ "Provider": "Satellink", "Email": ".pageme@satellink.net" }', 
	'{ "Provider": "Simple Freedom", "Email": "text.simplefreedom.net" }', 
	'{ "Provider": "Skytel Pagers", "Email": "email.skytel.com" }', 
	'{ "Provider": "Skytel Pagers", "Email": "skytel.com" }', 
	'{ "Provider": "Smart Telecom", "Email": "mysmart.mymobile.ph" }', 
	'{ "Provider": "Southern LINC", "Email": "page.southernlinc.com" }', 
	'{ "Provider": "Southwestern Bell", "Email": "email.swbw.com" }', 
	'{ "Provider": "Sprint PCS", "Email": "messaging.sprintpcs.com" }', 
	'{ "Provider": "Sprint", "Email": "sprintpaging.com" }', 
	'{ "Provider": "SunCom", "Email": "tms.suncom.com" }', 
	'{ "Provider": "SunCom", "Email": "xsuncom1.com" }', 
	'{ "Provider": "Surewest Communications", "Email": "mobile.surewest.com" }', 
	'{ "Provider": "T-Mobile", "Email": "tmomail.net" }', 
	'{ "Provider": "T-Mobile", "Email": "xvoicestream.net" }', 
	'{ "Provider": "TIM", "Email": "timnet.com" }', 
	'{ "Provider": "TSR Wireless", "Email": "alphame.com" }', 
	'{ "Provider": "TSR Wireless", "Email": "beep.com" }', 
	'{ "Provider": "Teleflip", "Email": "xteleflip.com" }', 
	'{ "Provider": "Teletouch", "Email": "pageme.teletouch.com" }', 
	'{ "Provider": "Telus", "Email": "msg.telus.com" }', 
	'{ "Provider": "The Indiana Paging Co", "Email": "pager.tdspager.com" }', 
	'{ "Provider": "Thumb Cellular", "Email": "xsms.thumbcellular.com" }', 
	'{ "Provider": "Tracfone", "Email": "xtxt.att.net" }', 
	'{ "Provider": "Triton", "Email": "tms.suncom.com" }', 
	'{ "Provider": "UCOM", "Email": "xpager.ucom.com" }', 
	'{ "Provider": "US Cellular", "Email": "xsmtp.uscc.net" }', 
	'{ "Provider": "US Cellular", "Email": "xuscc.textmsg.com" }', 
	'{ "Provider": "US West", "Email": "xuswestdatamail.com" }', 
	'{ "Provider": "US Cellular", "Email": "email.uscc.net" }', 
	'{ "Provider": "USA Mobility", "Email": "mobilecomm.net" }', 
	'{ "Provider": "Unicel", "Email": "utext.com" }', 
	'{ "Provider": "Verizon PCS", "Email": "myvzw.com" }', 
	'{ "Provider": "Verizon Pagers", "Email": "myairmail.com" }', 
	'{ "Provider": "Verizon", "Email": "vtext.com" }', 
	'{ "Provider": "Virgin Mobile", "Email": "vmobl.com" }', 
	'{ "Provider": "Virgin Mobile", "Email": "vxtras.com" }', 
	'{ "Provider": "WebLink Wiereless", "Email": "xairmessage.net" }', 
	'{ "Provider": "WebLink Wireless", "Email": "pagemart.net" }', 
	'{ "Provider": "West Central Wireless", "Email": "sms.wcc.net" }', 
	'{ "Provider": "Western Wireless", "Email": "cellularonewest.com" }', 
	'{ "Provider": "Wyndtell", "Email": "wyndtell.com" }'
];

$cmfa = json_decode($user->data()->mfa, true);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
		<?php if(Session::exists('success')): echo Session::flash('success'); endif; ?>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1><?php echo $GLOBALS['language']->get('multi-factor_authication');?></h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="mfa">
							<?php echo $GLOBALS['language']->get('enable').' '.$GLOBALS['language']->get('multi-factor_authication');?>:
							<input type="checkbox" name="enable-mfa" id="mfa" <?php echo ($cmfa['enable'] == 1)? "checked=\"checked\"":"";?>>
						</label>
					</div>
					<div class="form-group">
						<div class="radio">
						  <label>
						    <input type="radio" name="mfaType" id="email" value="email" <?php if($cmfa['type'] == "email"):?>checked<?php endif;?>>
						    <?php echo $GLOBALS['language']->get('email');?>
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="mfaType" id="sms" value="sms" <?php if($cmfa['type'] == "sms"):?>checked<?php endif;?>>
						   	<?php echo $GLOBALS['language']->get('sms');?>
						  </label>
						</div>
					</div>
					<div class="form-group">
						<select name="carrier" class="form-control">
						  <?php foreach($provider as $p): $pro = json_decode($p);?><option value="<?php echo escape($pro->Email);?>" <?php if($cmfa['semail'] === $pro->Email):?>selected<?php endif;?>><?php echo $pro->Provider;?></option><?php endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<input type="submit" value="submit" class="btn btn-primary">
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$(document).ready(function(){
				if($('#sms').is(':checked')) {

				}
				$("#sms").click(function(){
					if($('#sms').is(':checked')) {

					}
				});
			});
		</script>
	</body>
</html>