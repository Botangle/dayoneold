<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Botangle | Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#e7e7e7" style="padding: 40px 0 30px 20px;">
            @section('email-header')
            <img src="{{ URL::to('/img/logo.png') }}" alt="Botangle Logo" height="58" width="240">
            @show
        </td>
    </tr>
    <tr>
        <td style="padding: 30px 30px 30px 30px;">
            @yield('email-body')
        </td>
    </tr>
    <tr>
        <td bgcolor="#e7e7e7" style="padding: 30px 30px 30px 30px;">
            @yield('email-footer')
        </td>
    </tr>
</table>
</body>
</html>