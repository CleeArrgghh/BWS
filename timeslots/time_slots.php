<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Calendar & Time Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="../../bws_ui/timeslots/calendar_style/calendar.css" rel="stylesheet">
   
</head>

<body>

    <div class="container mt-5">
        <!-- Calendar Header -->
        <div class="calendar-header d-flex justify-content-between align-items-center mb-4">
            <button id="prev-month" class="btn btn-secondary" aria-label="Previous Month"><i class="fas fa-chevron-left"></i></button>
            <h3 id="current-month">October 2024</h3>
            <button id="next-month" class="btn btn-secondary" aria-label="Next Month"><i class="fas fa-chevron-right"></i></button>
        </div>

        <!-- Weekdays Labels -->
        <div class="weekdays">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <!-- Calendar Days -->
        <div id="calendar" class="calendar">
            <!-- Days will be dynamically generated -->
        </div>

        <!-- Time Slots for the Selected Day -->
        <div class="mt-4">
            <h4>Select a Time Slot</h4>
            <div id="time-slots" class="d-flex flex-wrap">
                <!-- Time slots will be dynamically generated -->
            </div>
            <!-- Information text for available and unavailable slots -->
            <div class="info-text">
                <span class="text-success">Green:</span> Available &nbsp; | &nbsp; 
                <span class="text-danger">Red:</span> Unavailable
            </div>
        </div>

        <!-- Calendar Footer (Confirm Button) -->
        <div class="calendar-footer mt-4">
            <button id="confirm-booking" class="btn btn-primary">Confirm Booking</button>
        </div>
    </div>

    <script>
        // Month and year control
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        let currentMonth = 9; // October (zero-based index)
        let currentYear = 2024;

        // Get today's date
        const today = new Date();
        const todayDate = today.getDate();
        const todayMonth = today.getMonth();
        const todayYear = today.getFullYear();

        // Display the current month and year
        function displayMonth() {
            document.getElementById('current-month').textContent = `${months[currentMonth]} ${currentYear}`;
        }

        // Generate the days for the current month
        function generateCalendarDays() {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('calendar-day');
                dayDiv.textContent = day;

                // Determine if the day should be disabled
                let isDisabled = false;

                if (currentYear < todayYear) {
                    isDisabled = true;
                } else if (currentYear === todayYear) {
                    if (currentMonth < todayMonth) {
                        isDisabled = true;
                    } else if (currentMonth === todayMonth && day < todayDate) {
                        isDisabled = true;
                    }
                }

                if (isDisabled) {
                    dayDiv.classList.add('disabled');
                } else {
                    dayDiv.addEventListener('click', () => selectDay(day));
                }

                calendar.appendChild(dayDiv);
            }
        }

        // Handle day selection
        function selectDay(day) {
            const days = document.querySelectorAll('.calendar-day');
            days.forEach(d => d.classList.remove('selected'));
            const selectedDay = days[day - 1];
            selectedDay.classList.add('selected');
            generateTimeSlots(day);
        }

        // Generate time slots for the selected day
        function generateTimeSlots(day) {
            const timeSlots = document.getElementById('time-slots');
            timeSlots.innerHTML = '';
            const timeSlotsData = [
                { time: '09:00 AM', available: true },
                { time: '10:00 AM', available: false },
                { time: '11:00 AM', available: true },
                { time: '12:00 PM', available: false },
                { time: '01:00 PM', available: true },
                { time: '02:00 PM', available: true },
                { time: '03:00 PM', available: false },
                { time: '04:00 PM', available: true },
                { time: '05:00 PM', available: false }
            ];

            timeSlotsData.forEach(slot => {
                const slotDiv = document.createElement('div');
                slotDiv.textContent = slot.time;
                slotDiv.classList.add('time-slot', slot.available ? 'available' : 'unavailable');

                slotDiv.addEventListener('click', () => {
                    selectTimeSlot(slotDiv);
                });

                timeSlots.appendChild(slotDiv);
            });
        }

        // Handle time slot selection
        function selectTimeSlot(selectedSlot) {
            const slots = document.querySelectorAll('.time-slot');
            slots.forEach(slot => slot.classList.remove('selected'));
            selectedSlot.classList.add('selected');
        }

        // Change to the previous month
        document.getElementById('prev-month').addEventListener('click', () => {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            displayMonth();
            generateCalendarDays();
        });

        // Change to the next month
        document.getElementById('next-month').addEventListener('click', () => {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            displayMonth();
            generateCalendarDays();
        });

        // Initial display
        displayMonth();
        generateCalendarDays();
    </script>
</body>

</html>
