
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<style type="text/css">
.vine-debug {
    position: absolute;
    top: 0;
    left: 0;
    height: 52px;
    width: 100%;
    z-index: 9999;
    background: #a41;
    color: #fff;
    overflow: hidden;
    border-bottom: 1px solid #000;
}

.vine-debug:hover {
    height: auto;
}

.vine-debug-inner {
    padding: 10px;
}

.vine-debug-title {
    font-family: Arial, Helvetica, Verdana;
    font-size: 20px;
    font-weight: bold;
    line-height: 32px;
    cursor: pointer;
}
</style>

<div class="vine-debug">
    <div class="vine-debug-inner">
        <div class="vine-debug-title">DEBUGGING DATA</div>
        <pre>%data%</pre>
    </div>
</div>
