/**
 * FUN Setup the Bootstrap Tooltip function.
 */
$(function() {
    $('[data-toggle="tooltip"]').tooltip({
        html: true
    })
});

/**
 * FUN Setup the Bootstrap Notify function.
 */
var type = ['primary', 'info', 'success', 'warning', 'danger'];
cms = {
    showNotification: function(color, from, align, message, icon) {
        $.notify({
            icon: icon,
            message: message,
            mouse_over: "pause",
        }, {
            type: type[color],
            timer: 8000,
            placement: {
                from: from,
                align: align
            }
        });
    }
};

/**
 * FUN CMS Calender Picker script.
 */
var color = "",
    firstdate = "";
$(document).ready(function() {
    var firstClick = true,
        startDate,
        endDate,
        arrivalDay = "",
        leavingDay = "",
        arrivalMonth = "",
        leavingMonth = "",
        datediff = 0,
        options = {year: 'numeric', month: '2-digit', day: '2-digit'},
        datemin = $('#min_nights_for_booking').val();

        /**
         * FUN check the arttribute of an element.
         */
        $.fn.hasAttr = function(name) {
            return this.attr(name) !== undefined;
        };
    
        /**
         * FUN Get the Start Date after first click and End Date after second click.
         * 
         * Show mark all cells between thes Dates and set the Date into the Event, Booking form
         */
        $(".month tbody td").bind('click', function() {
            var new_event = $(this).children('span:last-child'), // span new booking
                ul = $(this).find('ul'),                         // ul inside td
                li = $(this).find('ul li'),                      // li inside ul
                date = getDate($(this)),                         // date from the td (id)
                check_1 = $(this).hasClass('not-select-able'),   // check if is select able
                check_2 = $(this).hasAttr('data-set');           // second check if is select able 
            if (!check_2) {
                if (!check_1 && new_event.hasClass("book_able")) {
                    if (firstClick || firstClick == false && firstdate > date) {
                        clear_li()
                        startDate = date;
                        if (!li.find("span").hasClass("booked-start") || !li.find("span").hasClass("booked") || !li.find("span").hasClass("booked-end")) {
                            ul.append("<li><span style=\"border-color: "+color+"; background-color: "+color+";\" class=\"booked-new-start\"></span></li>");
                            arrivalDay = startDate.getDate();
                            arrivalMonth = startDate.getMonth() + 1;
                            $("#arrival").attr("value", startDate.toLocaleDateString(undefined, options));
                            firstClick = false;
                            firstdate = date;
                            // if the datemin is disabled
                            if(datemin == 0 || datemin == null) {
                                $("#leaving").attr("value", startDate.toLocaleDateString(undefined, options));
                                leavingDay = arrivalDay;
                                leavingMonth = arrivalMonth;
                                firstClick = true;
                                if($('#calendarModal').length > 0) {
                                    $("#calendarModal").modal("show");
                                }
                            }
                            return true;
                        } else {
                            cms.showNotification('4', 'top', 'center', notify_1, 'fas fa-bell');
                            firstClick = true;
                            return false;
                        }
                    } else {
                        if (startDate < date) {
                            endDate = date;
                            leavingDay = endDate.getDate();
                            leavingMonth = endDate.getMonth() + 1;
                            if(checkDate(startDate.toLocaleDateString(), endDate.toLocaleDateString())) {
                                if(cellsColorMarked(startDate, endDate)) {
                                    ul.append("<li><span style=\"border-color: "+color+"; background-color: "+color+";\" class=\"booked-new booked-new-end\"></span></li>");
                                    $("#leaving").attr("value", endDate.toLocaleDateString(undefined, options));
                                }
                            } else {
                                cms.showNotification('4', 'top', 'center', notify_2+' '+datemin+'.', 'fas fa-bell');
                                firstClick = false;
                                return false;
                            }
                            firstClick = true;
                            if($('#calendarModal').length > 0) {
                                $("#calendarModal").modal("show");
                            }
                        } else {
                            firstClick = true;
                            return true;
                        }
                    }
                } else {
                    if(new_event.hasClass("book_able")) {
                        cms.showNotification('4', 'top', 'center', notify_3, 'fas fa-bell');
                    } else {
                        cms.showNotification('4', 'top', 'center', notify_7, 'fas fa-bell');
                    }
                    return false;
                }
            } else {
                return false;
            }
        });

        /**
         * FUN check the Date and return true or false if date is not valide.
         */
        function checkDate(start = $('#arrival').val(), end = $('#leaving').val()) {
            // Check if the datmin is `0`
            if(datemin == 0 && $('#arrival').val() != "" && $('#leaving').val() != "") {
                return true;
            } else {
                // setup the variables and check if the Data is in range
                start = moment(start, "DD.MM.YYY");
                end = moment(end, "DD.MM.YYY");
                datediff = moment.duration(end.diff(start)).asDays() +1;
                // Check the Date if is in the same month
                if(arrivalMonth == leavingMonth) {
                    // Check the Date Day if is lower or higer than the given Day
                    if(arrivalDay < leavingDay && datediff >= datemin) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if(arrivalMonth < leavingMonth && datediff >= datemin) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }

        /**
         * FUN Marks all cells between the start Date & the End Date.
         */
        function cellsColorMarked(start_Date, end_Date) {
            var td = $("#" + start_Date.toISOString().substring(0, 10));
            while(start_Date < end_Date) {
                // Check if start_date is first add class booked-new
                if(start_Date.toISOString() == start_Date.toISOString()) {
                    td.find('ul li:last-child span').addClass("booked-new");
                }
                // Update the Date from the td
                start_Date.setDate(start_Date.getDate() + 1);
                // Get the td from the Table
                td = $("#" + start_Date.toISOString().substring(0, 10));
                // span new booking
                var ul = td.find('ul');
                // Check if start_date is same as end_date
                if(start_Date.toISOString() == end_Date.toISOString()) {
                    break;
                }
                // Add the New Event, Booking
                ul.append("<li><span style=\"border-color: "+color+"; background-color: "+color+";\" class=\"booked-new\"></span></li>");
            }
            return true;
        }

        /**
         * FUN Get the current date from the td Table cell.
         */
        function getDate(td) {
            // Get the current Date from the id of the clicked td field
            var cal = $(td).attr("id");
            var year = cal.match(/\d+/);
            var table = td.closest("table");
            var month = table.attr("id").match(/\d+/) - 1;
            // Create an new Date object and return it
            var date = new Date(Date.UTC(year, month, td.text(), 0, 0, 0, 0));
            return date;
        }

        /**
         * FUN Remove and Clear all li points if there is an New Booking on it.
         */
        function clear_li() {
            $('td ul li').each( function() {
                if($(this).find('span').hasClass('booked-new-start') || $(this).find('span').hasClass('booked-new') || $(this).find('span').hasClass('booked-new-end')) {
                    $(this).remove();
                }
            });
            $('#arrival').attr("value", "");
            $('#leaving').attr("value", "");
        }

        /**
         * FUN Delete selected Date if Click is outside of Calendar and Date is only firstdate.
         */
        $(document).click(function(event) { 
            $target = $(event.target);
            // Check if the clicked element is not the form submit button
            if(!$target.hasAttr('id')) {
                // Check if the element has an new event, booking inside the span
                if(!$target.closest('td').length && $('#arrival').val() != "" && firstClick == false || datemin == 0 && !$target.closest('td').length && $('#arrival').val() != "") {
                    clear_li();
                    firstClick = true;
                }
            }
        });

        /**
         * FUN Check the form submit before send to php script.
         */
        $("#booking_calendar_form").submit(function() {
            if($("#arrival").val() != "" && $("#leaving").val() != "") {
                if(checkDate()) {
                    return true;
                } else {
                    cms.showNotification('4', 'top', 'center', notify_4+' '+datemin, 'fas fa-bell');
                    $("#arrival").css({
                        "border-color": "#ff0505"
                    });
                    $("#leaving").css({
                        "border-color": "#ff0505"
                    });
                    return false;
                }
            } else {
                cms.showNotification('4', 'top', 'center', notify_6+' '+datemin, 'fas fa-bell');
                $("#arrival").css({
                    "border-color": "#ff0505"
                });
                $("#leaving").css({
                    "border-color": "#ff0505"
                });
                return false;
            }
        });

        /**
         * FUN Change the Persons from tha ajax request by changing the Event, Booking.
         */
        $('[id="events"]').change(function() {
            var formData = new FormData();
            formData.append("action", "getEvent"),
            formData.append("id", $(this).val());
            // Get the current Event, Booking from Database and set the Persons to the input field
            $.ajax({
                url: "./src/cms.php",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    // Check with select is active to set the response
                    if($('#persons').length > 0) {
                        var RS = jQuery.parseJSON(response);
                            $('#persons').html(RS[0]);
                            $('#second_person').attr("value", RS[1]);
                            $('#second_price').attr("value", RS[2]);
                    }
                },
                fail: function() {
                    cms.showNotification('4', 'top', 'center', notify_8, 'fas fa-bell');
                }
            });
        });

        /**
         * FUN Check the Persons val if there is an second_person.
         */
        $('[id="persons"]').change(function() {
            var second_prcie = $("#second_price").attr("value");
            var second_person = $("#second_person").attr("value");
            // Prüft die Anzahl an Personen
            if($(this).val() > second_person) {
                cms.showNotification('4', 'top', 'center', notify_5+' '+$(this).val()+' '+notify_5_1+' '+second_prcie+' '+notify_5_2, 'fas fa-bell');
            }
        });
});