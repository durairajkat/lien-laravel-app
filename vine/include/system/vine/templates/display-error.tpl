
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<style type="text/css">
.vine-error {
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

.vine-error a {
    color: #fff;
    text-decoration: underline;
}

.vine-error a:hover {
    text-decoration: none;
}
</style>

<div class="vine-error">
    <b>[%code%]</b> %message% ~ %file% ~ <i>line %line%</i>
</div>
