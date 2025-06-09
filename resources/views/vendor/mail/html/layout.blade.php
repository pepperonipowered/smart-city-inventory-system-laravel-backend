
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
    .inner-body {
        width: 100% !important;
    }
    .footer {
        width: 100% !important;
    }
}

@media only screen and (max-width: 500px) {
    .button {
        width: 100% !important;
    }
}
</style>
{{ $head ?? '' }}
</head>
<body style="background-color: #f8fafc;">
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" 
           style="background-color: #f8fafc; margin: 0; padding: 24px;">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" 
                       style="max-width: 600px; margin: 0 auto;">
                    {{ $header ?? '' }}

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" 
                                   cellspacing="0" role="presentation" 
                                   style="background-color: #ffffff; 
                                          border-radius: 12px;
                                          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                          margin-top: -20px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell" style="padding: 32px;">
                                        {{ Illuminate\Mail\Markdown::parse($slot) }}

                                        {{ $subcopy ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{ $footer ?? '' }}
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
