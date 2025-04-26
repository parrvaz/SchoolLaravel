<!-- resources/views/chart.blade.php -->
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نمودار خطی</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
<h2 style="text-align:center;">نمودار پیشرفت دانش‌آموز</h2>
<h4 style="text-align:center;">رگرسیون: {{$result['regression']}}</h4>
<h4 style="text-align:center;">نرخ رشد: {{$result['growthRate']}}</h4>
<h4 style="text-align:center;">تغییرات میانگین: {{$result['ac']}}</h4>
<h4 style="text-align:center;">ضریب همبستگی: {{$result['cc']}}</h4>

<form method="GET" action="{{ route('analysis') }}" style="text-align: center; margin-bottom: 30px;">
    <div style="display: inline-block; margin: 10px;">
        <label for="students" style="display: block; margin-bottom: 5px;">انتخاب دانش‌آموزان:</label>
        <select name="students[]" id="students" multiple style="width: 250px; height: 120px; padding: 5px; border-radius: 8px; border: 1px solid #ccc;">
            @foreach($students as $student)
                <option value="{{ $student->id }}" {{ in_array($student->id, request()->input('students', [])) ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="display: inline-block; margin: 10px;">
        <label for="courses" style="display: block; margin-bottom: 5px;">انتخاب دروس:</label>
        <select name="courses[]" id="courses" multiple style="width: 250px; height: 120px; padding: 5px; border-radius: 8px; border: 1px solid #ccc;">
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ in_array($course->id, request()->input('courses', [])) ? 'selected' : '' }}>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">
            ثبت
        </button>
    </div>
</form>
<canvas id="myChart" width="400" height="200"></canvas>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');

    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($xs) !!},
            datasets: [

                {
                    label: 'تراز',
                    data: {!! json_encode($ys) !!},
                    borderColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#students').select2({
            placeholder: "دانش‌آموزان را انتخاب کنید",
            allowClear: true
        });
        $('#courses').select2({
            placeholder: "دروس را انتخاب کنید",
            allowClear: true
        });
    });
</script>

</body>
</html>
