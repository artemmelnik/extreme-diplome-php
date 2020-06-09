const ADD_ANSWER_SELECTOR = '.js-add-answer';
const REMOVE_ANSWER_SELECTOR = '.js-remove-answer';
const CONTAINER_ANSWER_SELECTOR = '.js-container-answer';

const ANSWERS = [];

var ID = function () {
    return '_' + Math.random().toString(36).substr(2, 9);
};

function getAddAnswerTemplate(uniqueId) {
    return '<tr class="js-answer-id-' +uniqueId+ '">' +
        '<td><input type="text" class="form-control" name="answers[title][' + uniqueId + ']" placeholder="Ответ..."></td>' +
        '<td><div class="form-check">\n' +
        '    <input class="form-check-input" type="radio" name="answers[correctly]" value="' + uniqueId + '">\n' +
        '    <label class="form-check-label">Верный</label>\n' +
        '</div></td>' +
        '<td><a href="javascript:void(0)" class="btn btn-danger js-remove-answer" data-id="' + uniqueId + '">Удалить</a></td>' +
        '</tr>';
}

function getLastIndex() {
    return $(CONTAINER_ANSWER_SELECTOR).find('tr').length;
}

function renderAnswers() {
    for (var index in ANSWERS) {
        $(CONTAINER_ANSWER_SELECTOR).empty();
        $(CONTAINER_ANSWER_SELECTOR).append(getAddAnswerTemplate(index));
    }
}

$(document).on('click', ADD_ANSWER_SELECTOR, function (e) {
    var uniqueId = ID()
    $(CONTAINER_ANSWER_SELECTOR).append(getAddAnswerTemplate(uniqueId));
});

$(document).on('click', REMOVE_ANSWER_SELECTOR, function (e) {
    $('.js-answer-id-' + $(this).data('id')).remove();
});

const TIMER = $('#timer');

if (TIMER.data('s') > 0 || TIMER.data('i') > 0) {

    var date = new Date;
    var timeEnd = TIMER.data('time-end');
    var h = TIMER.data('h');
    var i = TIMER.data('i');
    var s = TIMER.data('s');
    var y = date.getFullYear();
    var d = date.getDate();
    var m = date.toLocaleString('en-EN', { month: 'short' });

    var countDownDate = new Date(m + ' ' + d + ', ' + y + ' ' + timeEnd).getTime();

    var x = setInterval(function() {

        var now = new Date().getTime();

        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = minutes + " мин. " + seconds + " сек. ";

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "Время закончилось.";
        }
    }, 1000);

}