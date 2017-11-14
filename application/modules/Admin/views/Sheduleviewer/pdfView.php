<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <!--<link href='//fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,bold' rel='stylesheet' type='text/css' />-->
    <!--<style>
      body {
      padding: 0;
      font-family: 'Montserrat', sans-serif;
      font-size: 8pt;
      }
    </style>-->
  </head>
 <body style="background-image:url('<?php echo base_url('uploads/images/background.jpg'); ?>');background-position: left top;background-repeat: no-repeat;border: 0; margin: 0; padding: 0; background-size: cover;">
    <table  width="100%" cellspacing="0" cellpadding="0"  border="0" style="border-spacing:0;">
      <tbody>
        <tr>
          <td>
            <table width="100%" cellspacing="0" cellpadding="0"  border="0" style="padding: 0; margin: 0;border-spacing:0;">
              <tbody>
                <tr>
                  <td style="padding:50px 32px 15px 32px;border:1px solid #FFFFFF">
                    <table width="100%"  cellpadding="0" border="0" >
                      <tbody>
                        <tr>
                          <td align="center">
                            <img style="max-width: 405px; margin-bottom: 30px;" src="<?php echo base_url('uploads/images/logo-pdf.png'); ?>" border="0" alt=""/>
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <p style="padding:20px;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: auto;font-size: 12px;line-height: 12px; font-weight: 200;">
                              <?=lang('pdf_message_1')?> <br/>
                              <br/>
                              <span style="text-transform: uppercase"><?=lang('pdf_message_2')?></span>
                            </p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="60%" align="center" cellspacing="0" cellpadding="0" style="border:1px solid #FFFFFF;font-size: 16px;padding: 15px;border-bottom:0px solid #FFFFFF; margin-top: 20px;" >
                      <tbody>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('reservation_code')?>:</td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['reservation_code'])?$pdfdata['reservation_code']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('reservation_email_id')?>:</td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['email'])?$pdfdata['email']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('number_of_people')?>:</td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['no_of_people'])?$pdfdata['no_of_people']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('zip_code')?>:</td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['zip_code'])?$pdfdata['zip_code']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('population')?>:  </td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['population_name'])?$pdfdata['population_name']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('agbar_client')?>: </td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['is_agbarCustomer'])?$pdfdata['is_agbarCustomer']:''?></td>
                        </tr>
                        <tr>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:left;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;"><?=lang('large_family_pdf')?>:</td>
                          <td  style="font-size: 12px; padding: 2px;margin:0;color:#FFFFFF;text-align:right;font-family: 'Montserrat', sans-serif;line-height: 12px;height: auto;font-weight: bold"><?=!empty($pdfdata['big_family'])?$pdfdata['big_family']:lang('no'); ?>  </td>
                        </tr>
                        <tr>
                          <td  style="height: 1px; border-bottom:1px solid #FFFFFF" colspan="2">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="60%" align="center" cellspacing="0" cellpadding="0" style="border:1px solid #FFFFFF;font-size: 11px;padding: 0px 15px 15px 15px;border-top:0px solid #FFFFFF" >
                      <tbody>
                        <tr>
                          <td align="center" style="padding:0px 0 10px 0;">
                            <img style="max-width: 88px;" src="<?=!empty($pdfdata['qr_code'])?$pdfdata['qr_code']:''?>" border="0" alt=""/>
                          </td>
                        </tr>
                        <tr>
                          <td  style="padding: 10px 0 10px 0;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: 14px;font-size:22px;height: auto;font-weight: bold"><?=!empty($pdfdata['datetime'])?$pdfdata['datetime']:''?></td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="60%" align="center" cellspacing="0" cellpadding="0" style="border:1px solid #FFFFFF;font-size: 11px;padding: 15px;border-top:0px solid #FFFFFF" >
                      <tbody>
                        <tr>
                          <td  style="padding:5px;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: 10px;height: auto; font-size: 10px;">
                            <?=lang('pdf_message_3')?><br/>
                            <?=lang('pdf_message_4')?> <br/>
                            <?=lang('pdf_message_5')?><br/>
                            <?=lang('pdf_message_6')?><br/>
                            <?=lang('pdf_message_7')?><br/>
                            <?=lang('pdf_message_8')?><br/>
                            <?=lang('pdf_message_9')?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="60%" align="center" cellspacing="0" cellpadding="0" style="font-size: 11px;padding: 15px; margin-bottom: 15px;" >
                      <tbody>
                        <tr>
                          <td  style="padding:0;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: 12px;font-size:12px;height: auto;"><u><?=lang('cancellation_code')?></u></td>

                          <td  style="padding:0;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: 12px;font-size:12px;height: auto;"><u><?=!empty($pdfdata['cancellation_code'])?$pdfdata['cancellation_code']:''?></u></td>
                        </tr>
                      </tbody>
                    </table>
                    <table width="98%" align="center" cellspacing="0" cellpadding="0" style="border:1px solid #FFFFFF;font-size: 11px;padding: 0px;" >
                      <tbody>
                        <tr>
                          <td  style="padding:15px;margin:0;color:#FFFFFF;text-align:center;background: #FFFFFF">
                            <img style="max-width: 45px;" src="<?php echo base_url('uploads/images/warning.jpg'); ?>" border="0" alt=""/>
                          </td>

                          <td valign="middle" style="padding: 15px;margin:0;color:#FFFFFF;text-align:center;font-family: 'Montserrat', sans-serif;line-height: 15px;font-size:9px;font-weight: bold; text-align: left;">
                            <?=lang('pdf_worning_1')?><br/>
                            <?=lang('pdf_worning_2')?><br/>
                            <?=lang('pdf_worning_3')?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>