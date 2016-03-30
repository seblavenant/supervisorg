$countdown = $("#timer");

var countdown = $countdown.countdown360({
    radius: 18,
    seconds: 10,
    label: [],
    fillStyle: "transparent",
    fontSize: 18,
    fontColor: '#FFFFFF',
    strokeStyle: '#FFFFFF',
    autostart: false,
    onComplete: function () {
        window.location.reload(false);
    }
});

countdown.start();
$refreshButton = $('#refresh');
$playButton = $('#play');
$stopButton = $('#stop');
$('#stop').click(function() {
    countdown.stop();
    $stopButton.hide();
    $playButton.show();
    return false;
});

$('#play').click(function() {
    countdown.start();
    $stopButton.show();
    $playButton.hide();
    return false;
});

$refreshButton.click(function() {
    window.location.reload(false);
});
