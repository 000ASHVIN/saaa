<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!--
-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Welcome to {{ config('app.name') }}</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<span style="background-color: #f1f1f1">
    <body bgcolor="#f1f1f1"
          style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background: #f1f1f1; color: #393939; font-family: Arial, Helvetica,; font-size: 14px; margin: 0 auto; padding: 0; width: 100% !important">
<style type="text/css"><!--
    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 400;
        src: local('Open Sans'), local('OpenSans'), url('https://fonts.gstatic.com/s/opensans/v13/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 700;
        src: local('Open Sans Bold'), local('OpenSans-Bold'), url('https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzInF5uFdDttMLvmWuJdhhgs.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: 800;
        src: local('Open Sans Extrabold'), local('OpenSans-Extrabold'), url('https://fonts.gstatic.com/s/opensans/v13/EInbV5DfGHOiMmvb1Xr-honF5uFdDttMLvmWuJdhhgs.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Open Sans';
        font-style: italic;
        font-weight: 400;
        src: local('Open Sans Italic'), local('OpenSans-Italic'), url('https://fonts.gstatic.com/s/opensans/v13/xjAJXh38I15wypJXxuGMBp0EAVxt0G0biEntp43Qt6E.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Open Sans';
        font-style: italic;
        font-weight: 700;
        src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url('https://fonts.gstatic.com/s/opensans/v13/PRmiXeptR36kaC0GEAetxp_TkvowlIOtbR7ePgFOpF4.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Montserrat';
        font-style: normal;
        font-weight: 400;
        src: local('Montserrat-Regular'), url('https://fonts.gstatic.com/s/montserrat/v6/zhcz-_WihjSQC0oHJ9TCYC3USBnSvpkopQaUR-2r7iU.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Montserrat';
        font-style: normal;
        font-weight: 700;
        src: local('Montserrat-Bold'), url('https://fonts.gstatic.com/s/montserrat/v6/IQHow_FEYlDC4Gzy_m8fcvEr6Hm6RMS0v1dtXsGir4g.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        src: local('Roboto Light'), local('Roboto-Light'), url('https://fonts.gstatic.com/s/roboto/v15/Hgo13k-tfSpn0qi1SFdUfaCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        src: local('Roboto'), local('Roboto-Regular'), url('https://fonts.gstatic.com/s/roboto/v15/zN7GBFwfMP4uA6AR0HCoLQ.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 500;
        src: local('Roboto Medium'), local('Roboto-Medium'), url('https://fonts.gstatic.com/s/roboto/v15/RxZJdnzeo3R5zSexge8UUaCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        src: local('Roboto Bold'), local('Roboto-Bold'), url('https://fonts.gstatic.com/s/roboto/v15/d-6IYplOFocCacKzxwXSOKCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: italic;
        font-weight: 300;
        src: local('Roboto Light Italic'), local('Roboto-LightItalic'), url('https://fonts.gstatic.com/s/roboto/v15/7m8l7TlFO-S3VkhHuR0at50EAVxt0G0biEntp43Qt6E.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: italic;
        font-weight: 400;
        src: local('Roboto Italic'), local('Roboto-Italic'), url('https://fonts.gstatic.com/s/roboto/v15/W4wDsBUluyw0tK3tykhXEfesZW2xOQ-xsNqO47m55DA.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        font-style: normal;
        font-weight: 300;
        src: local('Lato Light'), local('Lato-Light'), url('https://fonts.gstatic.com/s/lato/v11/nj47mAZe0mYUIySgfn0wpQ.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        font-style: normal;
        font-weight: 400;
        src: local('Lato Regular'), local('Lato-Regular'), url('https://fonts.gstatic.com/s/lato/v11/v0SdcGFAl2aezM9Vq_aFTQ.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        font-style: normal;
        font-weight: 700;
        src: local('Lato Bold'), local('Lato-Bold'), url('https://fonts.gstatic.com/s/lato/v11/DvlFBScY1r-FMtZSYIYoYw.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        font-style: italic;
        font-weight: 300;
        src: local('Lato Light Italic'), local('Lato-LightItalic'), url('https://fonts.gstatic.com/s/lato/v11/2HG_tEPiQ4Z6795cGfdivKCWcynf_cDxXwCLxiixG1c.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Lato';
        font-style: italic;
        font-weight: 400;
        src: local('Lato Italic'), local('Lato-Italic'), url('https://fonts.gstatic.com/s/lato/v11/LqowQDslGv4DmUBAfWa2Vw.ttf') format('truetype');
    }

    .ReadMsgBody {
        width: 100%;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass {
        line-height: 100%;
    }

    img {
        display: block;
    }

    body {
        padding: 0;
        width: 100% !important;
    }

    body {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }

    .ReadMsgBody {
        width: 100%;
    }

    .ExternalClass {
        width: 100%;
    }

    body {
        background-color: #f1f1f1;
        font-family: Arial, Helvetica,;
        font-size: 14px;
        color: #393939;
        margin: 0 auto;
    }

    @media (max-width: 768px) and (min-width: 421px) {
        body {
            margin: 0 auto !important;
            width: 600px !important;
        }

        img {
            height: auto !important;
            max-width: 100% !important;
        }

        .wrapper_table {
            width: 600px !important;
            margin: 0 auto;
            text-align: center;
            background-size: 100% 100% !important;
        }

        .content {
            width: 600px !important;
        }

        .content img {
            max-width: 600px !important;
        }

        .padding {
            width: 20px !important;
        }

        .content_row {
            width: 560px !important;
        }

        .content_row img {
            height: auto !important;
            max-width: 560px !important;
        }

        .content_row_inner {
            width: 520px !important;
        }

        .col_md_5 {
            width: 104px !important;
            display: inline-block;
        }

        .col_md_4 {
            width: 132.5px !important;
            display: inline-block;
        }

        .col_md_3 {
            width: 180px !important;
            display: inline-block;
        }

        .col_md_2 {
            width: 275px !important;
            display: inline-block;
        }

        .col_md_23 {
            width: 367px !important;
            display: inline-block;
        }

        .col_md_13 {
            width: 183px !important;
            display: inline-block;
        }

        .col_md_4 img {
            max-width: 130px !important;
            height: auto !important;
        }

        .col_md_4_fullwidth {
            width: 150px !important;
            display: table-cell;
        }

        .col_md_3_fullwidth {
            width: 200px !important;
            display: table-cell;
        }

        .col_md_2_fullwidth {
            width: 300px !important;
            display: table-cell;
        }

        .col_md_23_fullwidth {
            width: 400px !important;
            display: table-cell;
        }

        .col_md_13_fullwidth {
            width: 200px !important;
            display: table-cell;
        }

        .col_md_4_fullwidth img {
            max-width: 150px !important;
            height: auto !important;
        }

        .col_md_3_fullwidth img {
            max-width: 200px !important;
            height: auto !important;
        }

        .col_md_2_fullwidth img {
            max-width: 300px !important;
            height: auto !important;
        }

        .col_md_13_fullwidth img {
            max-width: 200px !important;
            height: auto !important;
        }

        .col_md_23_fullwidth img {
            max-width: 400px !important;
            height: auto !important;
        }

        .col_md_23 img {
            max-width: 100% !important;
            display: block;
            height: auto !important;
        }

        .col_md_13 img {
            max-width: 100% !important;
            display: block;
            height: auto !important;
        }

        .col_md_2 img {
            max-width: 100% !important;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        .col_md_3 img {
            max-width: 100% !important;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        .col_md_4_fullwidth_img {
            width: 150px !important;
            height: auto !important;
        }

        .col_md_3_fullwidth_img {
            width: 200px !important;
            height: auto !important;
        }

        .col_md_2_fullwidth_img {
            width: 300px !important;
            height: auto !important;
        }

        .col_md_23_fullwidth_img {
            width: 400px !important;
            height: auto !important;
        }

        .col_md_13_fullwidth_img {
            width: 200px !important;
            height: auto !important;
        }

        .col_md_4_fullwidth_margin {
            width: 142px !important;
            display: table-cell;
        }

        .col_md_3_fullwidth_margin {
            width: 193px !important;
            display: table-cell;
        }

        .col_md_2_fullwidth_margin {
            width: 295px !important;
            display: table-cell;
        }

        .col_md_23_fullwidth_margin {
            width: 397px !important;
            display: table-cell;
        }

        .col_md_13_fullwidth_margin {
            width: 193px !important;
            display: table-cell;
        }

        .col_md_4_fullwidth_margin img {
            max-width: 142px !important;
            height: auto !important;
        }

        .col_md_3_fullwidth_margin img {
            max-width: 193px !important;
            height: auto !important;
        }

        .col_md_2_fullwidth_margin img {
            max-width: 295px !important;
            height: auto !important;
        }

        .col_md_13_fullwidth_margin img {
            max-width: 193px !important;
            height: auto !important;
        }

        .col_md_23_fullwidth_margin img {
            max-width: 397px !important;
            height: auto !important;
        }

        .col_md_3_inner {
            width: 166px !important;
            display: inline-block;
        }

        .col_md_3_inner img {
            max-width: 166px !important;
            height: auto !important;
        }

        .col_md_5_space {
            width: 10px;
        }

        .col_md_4_space {
            width: 10px;
        }

        .col_md_3_space {
            width: 10px;
        }

        .col_md_2_space {
            width: 10px;
        }

        .col_md_23_space {
            width: 10px;
        }

        .notablet {
            display: none !important;
        }

        .noresponsive {
            display: none !important;
        }

        .font10 {
            font-size: 15px !important;
        }

        .font15 {
            font-size: 20px !important;
        }

        .font20 {
            font-size: 25px !important;
        }

        .font30 {
            font-size: 35px !important;
        }

        .height10 {
            height: 15px !important;
            display: block !important;
        }

        .height15 {
            height: 20px !important;
            display: block !important;
        }

        .height20 {
            height: 25px !important;
            display: block !important;
        }

        .height25 {
            height: 30px !important;
            display: block !important;
        }

        .height30 {
            height: 35px !important;
            display: block !important;
        }

        .height35 {
            height: 40px !important;
            display: block !important;
        }

        .height40 {
            height: 45px !important;
            display: block !important;
        }

        .noborder {
            border: 0 !important;
        }

        .tablet_margin_top_20 {
            margin-top: 20px !important;
        }

        .tablet_margin_bottom_20 {
            margin-bottom: 20px !important;
        }

        .tablet_margin_bottom_30 {
            margin-bottom: 30px !important;
        }

        .tablet_margin_top_20 {
            margin-top: 20px !important;
        }

        .tablet_margin_bottom_20 {
            margin-bottom: 20px !important;
        }

        .tablet_margin_bottom_30 {
            margin-bottom: 30px !important;
        }

        .stack_responsive {
            width: 100% !important;
            display: inline-block;
        }

        .stacked {
            display: block;
            clear: both;
            float: none;
        }

        .text_center_responsive {
            text-align: center !important;
        }

        .align_right {
            text-align: right !important;
        }

        .align_left {
            text-align: left !important;
        }

        .align_right_tablet {
            text-align: right !important;
        }

        .align_left_tablet {
            text-align: left !important;
        }

        .align_right_responsive {
            text-align: right !important;
        }

        .align_left_responsive {
            text-align: left !important;
        }

        .align_right {
            text-align: right !important;
        }

        .align_left {
            text-align: left !important;
        }

        .image img {
            height: auto !important;
            width: 100% !important;
        }

        .responsive_centered_table {
            margin: 0 auto !important;
            text-align: center !important;
        }

        .small_image {
            height: auto !important;
            max-width: 210px !important;
        }

        .small_image img {
            height: auto !important;
            max-width: 210px !important;
        }

        .small_image1 img {
            height: auto !important;
            max-width: 190px !important;
        }

        .tablet_height10 {
            height: 10px !important;
        }

        .image_repair_img {
            width: 255px !important;
            height: auto !important;
        }

        .width40 {
            width: 40px !important;
        }

        .right_dotted_grey_border {
            border-right: 1px dotted #c6c6c6;
        }

        .bottom_dotted_grey_border {
            border-bottom: 1px dotted #c6c6c6;
        }
    }

    @media (max-width: 421px) {
        body {
            margin: 0 auto !important;
            width: 320px !important;
        }

        img {
            height: auto !important;
            max-width: 100% !important;
        }

        table[class="wrapper_table"] {
            width: 320px !important;
            background-size: cover !important;
            background-position: center !important;
        }

        table[class="wrapper_table"] .content {
            width: 320px !important;
        }

        table[class="wrapper_table"] .content img {
            max-width: 320px !important;
        }

        table[class="wrapper_table"] .padding {
            width: 10px !important;
        }

        table[class="wrapper_table"] .content_row {
            width: 300px !important;
        }

        table[class="wrapper_table"] .content_row img {
            max-width: 300px !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .content_row_inner {
            width: 280px !important;
        }

        table[class="mobile_centered_table"] {
            margin: 0 auto !important;
            text-align: center !important;
        }

        td[class="mobile_centered_table"] {
            margin: 0 auto !important;
            text-align: center !important;
        }

        table[class="wrapper_table"] .col_md_5 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_4 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_3 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_2 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_3_inner {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_23 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_13 {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_23_fullwidth {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_13_fullwidth {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_4_fullwidth {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_3_fullwidth {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_2_fullwidth {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_23_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_13_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_4_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_3_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_2_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_3_fullwidth_margin {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_2_fullwidth_margin img {
            width: 100% !important;
            display: block !important;
        }

        table[class="wrapper_table"] .col_md_5_space {
            display: none !important;
            margin-bottom: 20px;
        }

        table[class="wrapper_table"] .col_md_4_space {
            display: none !important;
            margin-bottom: 20px;
        }

        table[class="wrapper_table"] .col_md_3_space {
            display: none !important;
            margin-bottom: 20px;
        }

        table[class="wrapper_table"] .col_md_2_space {
            display: none !important;
            margin-bottom: 20px;
        }

        table[class="wrapper_table"] .col_md_23_space {
            display: none !important;
            margin-bottom: 20px;
        }

        table[class="wrapper_table"] .col_md_5 img {
            max-width: 100%;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .col_md_4 img {
            max-width: 100%;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .col_md_3 img {
            max-width: 100%;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .col_md_2 img {
            max-width: 100%;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .col_md_1 img {
            max-width: 100%;
            display: block;
            width: auto !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .col_md_23 img {
            display: block;
            height: auto !important;
        }

        table[class="wrapper_table"] .font10 {
            font-size: 10px !important;
        }

        table[class="wrapper_table"] .font15 {
            font-size: 15px !important;
        }

        table[class="wrapper_table"] .font20 {
            font-size: 20px !important;
        }

        table[class="wrapper_table"] .font30 {
            font-size: 30px !important;
        }

        table[class="wrapper_table"] .mob_font10 {
            font-size: 10px !important;
        }

        table[class="wrapper_table"] .mob_font15 {
            font-size: 15px !important;
        }

        table[class="wrapper_table"] .mob_font20 {
            font-size: 20px !important;
        }

        table[class="wrapper_table"] .height10 {
            height: 10px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height15 {
            height: 15px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height20 {
            height: 20px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height25 {
            height: 25px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height30 {
            height: 30px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height35 {
            height: 35px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .height40 {
            height: 40px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height10 {
            height: 10px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height15 {
            height: 15px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height20 {
            height: 20px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height25 {
            height: 25px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height30 {
            height: 30px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height35 {
            height: 35px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .mobile_height40 {
            height: 40px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .noresponsive {
            display: none !important;
        }

        table[class="wrapper_table"] .nomobile {
            display: none !important;
        }

        table[class="wrapper_table"] .text_center {
            text-align: center !important;
        }

        table[class="wrapper_table"] .stacked_fullwidth {
            width: 100%;
            display: block !important;
        }

        table[class="wrapper_table"] .stacked_fullwidth_centered {
            max-width: 100% !important;
            display: block !important;
            margin: 0 auto !important;
        }

        table[class="wrapper_table"] .stacked {
            display: block;
            clear: both;
            float: none;
        }

        table[class="wrapper_table"] .noborder {
            border: 0 !important;
        }

        table[class="wrapper_table"] .noborder_mobile {
            border: 0 !important;
        }

        table[class="wrapper_table"] .margin_bottom_10 {
            margin-bottom: 10px !important;
        }

        table[class="wrapper_table"] .mobile_margin_bottom_10 {
            margin-bottom: 10px !important;
        }

        table[class="wrapper_table"] .margin_top_20 {
            margin-top: 20px !important;
        }

        table[class="wrapper_table"] .mobile_margin_top_20 {
            margin-top: 20px !important;
        }

        table[class="wrapper_table"] .margin_bottom_20 {
            margin-bottom: 20px !important;
        }

        table[class="wrapper_table"] .mobile_margin_bottom_20 {
            margin-bottom: 20px !important;
        }

        table[class="wrapper_table"] .margin_bottom_30 {
            margin-bottom: 30px !important;
        }

        table[class="wrapper_table"] .mobile_margin_bottom_30 {
            margin-bottom: 30px !important;
        }

        table[class="wrapper_table"] .stack_responsive {
            width: 100%;
            display: block !important;
        }

        table[class="wrapper_table"] .header_group {
            display: table-header-group !important;
            margin-bottom: 20px !important;
        }

        table[class="wrapper_table"] .footer_group {
            display: table-footer-group !important;
        }

        table[class="wrapper_table"] .text_center_responsive {
            text-align: center;
        }

        table[class="wrapper_table"] .text_center_responsive {
            text-align: center;
        }

        table[class="wrapper_table"] .align_right {
            text-align: right !important;
        }

        table[class="wrapper_table"] .align_left {
            text-align: left !important;
        }

        table[class="wrapper_table"] .align_right_mobile {
            text-align: right !important;
        }

        table[class="wrapper_table"] .align_left_mobile {
            text-align: left !important;
        }

        table[class="wrapper_table"] .align_right_responsive {
            text-align: right !important;
        }

        table[class="wrapper_table"] .align_left_responsive {
            text-align: left !important;
        }

        table[class="wrapper_table"] .responsive_centered_table {
            margin: 0 auto !important;
            text-align: center !important;
        }

        table[class="wrapper_table"] .nobg_mobile {
            background: none !important;
            background-color: #f8f8f8 !important;
        }

        table[class="wrapper_table"] .image img {
            display: block;
            width: 100% !important;
            height: auto !important;
        }

        table[class="wrapper_table"] .desktop_hide {
            max-height: none !important;
            font-size: 12px !important;
            display: block !important;
        }

        table[class="wrapper_table"] .desktop_hide_img {
            max-height: none !important;
            width: auto !important;
            display: block !important;
            visibility: visible !important;
        }

        table[class="wrapper_table"] .background {
            background-size: cover !important;
            background-position: center top !important;
            background-repeat: no-repeat !important;
        }

        table[class="wrapper_table"] .no_letter_spacing {
            letter-spacing: normal !important;
        }

        table[id="nobg_mobile_hero"] {
            background: none !important;
            background-color: #222325 !important;
        }

        table[class="wrapper_table"] .mobile_width280 {
            width: 280px !important;
        }

        table[class="wrapper_table"] .mobile_width45 {
            width: 45px !important;
        }

        table[class="wrapper_table"] .small_image img {
            height: auto !important;
            width: 100% !important;
        }

        table[class="wrapper_table"] .small_image1 img {
            height: auto !important;
            width: 100% !important;
        }

        table[class="wrapper_table"] .white_bg_mobile {
            background-color: #ffffff !important;
        }

        table[class="wrapper_table"] .width100 {
            width: 100px !important;
        }

        table[class="wrapper_table"] .width120 {
            width: 120px !important;
        }

        table[class="wrapper_table"] .right_dotted_grey_border {
            border: none !important;
        }

        table[class="wrapper_table"] .bottom_dotted_grey_border {
            border: none !important;
        }
    }

    --></style>
<div class="span" style="background-color: #f1f1f1;">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="wrapper_table" mc:repeatable=""
       style="max-width: 800px;" width="800">
<tbody>
<tr>
<td class="noresponsive">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="800px">
<tbody>
<tr>
<td border="0" cellpadding="0" cellspacing="0" height="1px; min-width: 800px;" style="line-height: 1px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="wrapper_table"
       style="background: no-repeat center top / 100% 100%; max-width: 800px;" width="800">
<tbody>
<tr>
<td bgcolor="#ffffff" class="content white_bg" style="background: #ffffff;" width="800">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td class="space_class" colspan="3" height="20" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
</tr>
<tr>
<td class="padding" width="20">&nbsp;</td>
<td align="center" class="content_row" width="760">
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td align="left" class="col_md_2 mobile_margin_bottom_20" width="370">
<table border="0" cellpadding="0" cellspacing="0" class="mobile_centered_table">
<tbody>
<tr>
<td><a href="#" style="text-decoration: none;" target="_blank"><img alt="" border="0" height="56"
                                                                    src="{{ url('/')  }}/assets/themes/taxfaculty/img/logo.png"
                                                                    style="display: block;" title="" width="242"/> </a></td>
</tr>
</tbody>
</table>
</td>
<td class="col_md_2_space" width="20">&nbsp;</td>
<td align="right" class="col_md_2" width="370">
<table border="0" cellpadding="0" cellspacing="0" class="mobile_centered_table">
<tbody>
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>&nbsp;</td>
<td width="5">&nbsp;</td>
<td align="center" class="weight_700 uppercase montserrat dark_grey_text"
    style="color: #35373e; font-family: Helvetica; font-size: 12px; font-weight: bold; line-height: 12px; text-transform: uppercase;"><a
            class="dark_grey_text" href="#" style="color: #35373e; text-decoration: none;"
            target="_blank">Home </a></td>
</tr>
</tbody>
</table>
</td>
<td class="padding" width="40">&nbsp;</td>
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>&nbsp;</td>
<td width="5">&nbsp;</td>
<td align="center" class="weight_700 uppercase montserrat dark_grey_text"
    style="color: #35373e; font-family: Helvetica; font-size: 12px; font-weight: bold; line-height: 12px; text-transform: uppercase;">CPD</td>
</tr>
</tbody>
</table>
</td>
<td class="padding" width="40">&nbsp;</td>
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>&nbsp;</td>
<td width="5">&nbsp;</td>
<td align="center" class="weight_700 uppercase  montserrat dark_grey_text"
    style="color: #35373e; font-family: Helvetica; font-size: 12px; font-weight: bold; line-height: 12px; text-transform: uppercase;">Store</td>
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
</td>
<td class="padding" width="20">&nbsp;</td>
</tr>
<tr>
<td class="space_class" colspan="3" height="20" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#ebebeb" class="light_grey_bg1 space_class" colspan="3" height="2"
    style="background: #ebebeb; font-size: 1px; line-height: 1px;" width="100%">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="wrapper_table"
       style="background: no-repeat center top / 100% 100%; max-width: 800px;" width="800">
<tbody>
<tr>
<td class="content space_class" height="10" style="font-size: 1px; line-height: 1px;" width="800">&nbsp;</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="wrapper_table"
       style="background: no-repeat center top / 100% 100%; max-width: 800px;" width="800">
<tbody>
<tr>
<td bgcolor="#ffffff" class="content white_bg" style="background: #ffffff;" width="800">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td class="padding" width="20">&nbsp;</td>
<td align="center" class="content_row" width="760">
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td class="height15 space-class" height="20">&nbsp;</td>
</tr>
<tr>
<td align="left" class="weight_400 lato lighter_grey_text"
    style="color: #9e9e9e; font-family: Helvetica; font-size: 13px; font-weight: 400; line-height: 23px;">
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">Dear {{ $user->first_name }}</span></span></p>
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;"></span></span></p>
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">We have noticed that you have selected to pay your CPD subscription by credit card, however you have no Credit Card currently linked to your profile.</span></span></p>
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;"></span></span></p>
<p style="color: #9e9e9e; font-size: 13px;"><strong><span color="#000000" style="color: #000000;"><span
                    style="font-size: 14px;">How do I link my Credit Card?</span></span></strong></p>
<ul>
<li style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">Login to your {{ config('app.name') }} Profile.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">Click on the My Billing tab.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">Click on Add Credit Card.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">Enter your Credit Card / Debit Card Number</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">Enter your the Card Holder Name</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">Select your expiry month.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">Select your Expiry Year.&nbsp;</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">Enter your CVV code.</span></span></li>
</ul>
<p><span color="#000000" style="color: #000000;"><span style="font-size: 14px;"></span></span></p>
<p><strong><span color="#000000" style="color: #000000;"><span
                    style="font-size: 14px;">Did you know the following:</span></span></strong></p>
<ul>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">That you can add multiple credit cards to your account? You have the ability to set one of your credit cards as your primary cards, This will ensure that all the charges will be deducted from your primary card.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">You can now use your debit card as a credit card, currently in South Africa all major banks has implemented this functionality for users to use their debit cards as a credit card.</span></span></li>
<li><span color="#000000" style="color: #000000;"><span style="font-size: 14px;">To link your debit card to your account, simply follow the same steps as you would have followed to save your credit card. If your debit card has been enrolled for 3Dsecure you will receive an SMS with a OTP that you need to confirm.</span></span></li>
</ul>
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;"></span></span><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;">For any queries, please feel free to contact us on&nbsp;012 943 7002 or kindly send us an email to&nbsp;{{ config('app.email') }}.</span></span></p>
    <br/>
<p style="color: #9e9e9e; font-size: 13px;"><span color="#000000" style="color: #000000;"><span
                style="font-size: 14px;"></span></span></p>
</td>
</tr>
</tbody>
</table>
</td>
<td class="padding" width="20">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#ebebeb" class="light_grey_bg1 space_class" colspan="3" height="2"
    style="background: #ebebeb; font-size: 1px; line-height: 1px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="wrapper_table"
       style="background: no-repeat center top / 100% 100%; max-width: 800px;" width="800">
<tbody>
<tr>
<td class="content space_class" height="10" style="font-size: 1px; line-height: 1px;" width="800">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="wrapper_table" width="800" cellspacing="0" cellpadding="0" border="0" align="center"
       style="background: no-repeat center top / 100% 100%; max-width: 800px;">
<tbody>
<tr>
<td class="content white_bg" data-color-whitebg="background-color" width="800" style="background: #8cc03c;"
    bgcolor="rgb(23, 49, 117)">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td colspan="3" height="19" class="space_class" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
</tr>
<tr>
<td class="padding" width="20"></td>
<td class="content_row" width="760" align="center">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tbody>
<tr>
<td align="center" data-editable="" class="weight_400 lato dark_grey_text" data-color-darkgreytext="color"
    data-font-paragraph="" data-lh-paragraph=""
    style="color: #ffffff; font-family: Helvetica; font-size: 12px; font-weight: 400; line-height: 12px;">Copyright 2017 {{ config('app.name') }} <br/><br/></td>
</tr>
</tbody>
</table>
</td>
<td class="padding" width="20"></td>
</tr>
<tr>
<td colspan="3" height="19" class="space_class" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
</tr>
<tr>
<td class="light_grey_bg1 space_class" data-color-lightgreybg1="background-color" height="2" colspan="3"
    style="background: #ebebeb; font-size: 1px; line-height: 1px;" bgcolor="#ebebeb">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</body>
</span>
</html>