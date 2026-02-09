
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<style type="text/css">
.vine-exception {
    position: relative;
    left: 10%;
    padding: 10px;
    margin: 10px 0;
    width: 80%;
    background: #a41;
    color: #fff;
    border: 1px solid #000;
    font-family: verdana, arial, helvetica;
    font-size: 11px;
    z-index: 999;
}

.vine-exception-type {
    font-size: 120%;
}

.vine-exception-details {
    padding-bottom: 10px;
}
</style>

<div class="vine-exception">
    <div class="vine-exception-details">
        <b class="vine-exception-type">%type%</b><br /><br />
        <b>File:</b> %file%<br />
        <b>Line:</b> %line%<br />
        <b>Message:</b> %message%
    </div>

    <div>
        %trace%
    </div>
</div>
