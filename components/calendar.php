<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kawaii Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script> -->
    <!-- Include Thai Locale -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/locales-all.min.js"></script> -->


    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

    <!-- FullCalendar JavaScript -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

    <style>
        .fc-event-tooltip {
            position: absolute;
            z-index: 10001;
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 5px;
            border-radius: 3px;
            font-size: 12px;
            pointer-events: none;
        }

        .fc-day-today {
            background-color: #ffcc00 !important;
            color: #000000 !important;
        }

        #calendar {
            max-width: 600px;
            margin: 0 auto 20px auto;
        }


        .fc {
            font-size: 0.2em;
        }

        .fc-header-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .fc-toolbar-chunk {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .fc-toolbar-chunk {
                flex-basis: 100%;
                text-align: center;
            }

            .fc-toolbar-title {
                font-size: 1.2rem;
            }
        }

        .fc-toolbar h2 {
            font-size: 1.2em;
        }

        .fc-button-group .fc-button {
            margin-right: 10px;
        }

        .fc-button-group .fc-button:last-child {
            margin-right: 0;
        }

        .fc .fc-toolbar>*> :first-child {
            margin-left: 20px;
        }
    </style>

</head>

<body>
    <!-- ส่วนปฏิทิน -->
    <div id="calendar"></div>

    <!-- คำอธิบายใต้ปฏิทิน -->
    <div class="container mt-4 mb-4">
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex align-items-center me-3">
                <span class="d-inline-block" style="width: 20px; height: 20px; background-color: #28a745; border-radius: 3px; margin-right: 5px;"></span>
                <span>จองแล้ว</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="d-inline-block" style="width: 20px; height: 20px; background-color: #ffcc00; border-radius: 3px; margin-right: 5px;"></span>
                <span>วันนี้</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                events: '../../components/fetch-events.php',

                eventColor: '#378006',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'วันนี้',
                    month: 'เดือน',
                    week: 'สัปดาห์',
                    day: 'วัน'
                },
                titleFormat: {
                    year: 'numeric',
                    month: 'long'
                },
                displayEventEnd: true,
                eventMouseEnter: function(info) {
                    var startDate = new Date(info.event.start);
                    var endDate = info.event.end ? new Date(info.event.end) : null;

                    if (endDate) {
                        endDate.setDate(endDate.getDate() - 1);
                    }

                    var cusName = info.event.extendedProps.cus_Name;
                    var dateText = `${startDate.toLocaleDateString()} - ${endDate ? endDate.toLocaleDateString() : ''}`;
                    var bookingH_No = info.event.extendedProps.BookingH_No;

                    var tooltip = document.createElement('div');
                    tooltip.classList.add('fc-event-tooltip');
                    tooltip.innerHTML = `
                <strong>${cusName}</strong><br>
                รหัสการจอง: ${bookingH_No}<br>
                วันที่: ${dateText}<br>
            `;
                    document.body.appendChild(tooltip);

                    tooltip.style.left = info.jsEvent.pageX + 'px';
                    tooltip.style.top = info.jsEvent.pageY + 'px';

                    info.el.tooltip = tooltip;
                },

                eventMouseLeave: function(info) {
                    if (info.el.tooltip) {
                        info.el.tooltip.remove();
                        info.el.tooltip = null;
                    }
                }
            });

            calendar.render();
        });
    </script>


</body>

</html>