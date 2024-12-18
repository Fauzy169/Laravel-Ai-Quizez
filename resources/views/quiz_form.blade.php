<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Quiz</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv+5XXW9n8hb6zK2TFo+Yj9eOxxiEGzZl1DqYbgIq8fM5B6v7JHZ9f9dToE" crossorigin="anonymous">

    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        #quiz-result {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        pre {
            white-space: pre-wrap; 
            word-wrap: break-word;
        }
        .result-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .result-item {
            margin-bottom: 10px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Quiz</h1>

        <form id="quiz-form">
            @csrf
            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <input type="text" id="kategori" name="kategori" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah:</label>
                <input type="number" id="jumlah" name="jumlah" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="kesulitan">Kesulitan:</label>
                <input type="text" id="kesulitan" name="kesulitan" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="level">Level:</label>
                <input type="text" id="level" name="level" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Generate Quiz</button>
        </form>

        <div id="quiz-result"></div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#quiz-form').on('submit', function(e) {
                e.preventDefault();

                // Ambil data dari form
                var formData = {
                    kategori: $('#kategori').val(),
                    jumlah: $('#jumlah').val(),
                    kesulitan: $('#kesulitan').val(),
                    level: $('#level').val(),
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: '/generate-quiz',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Clear previous result
                        $('#quiz-result').empty();
                        
                        let quizHTML = `<div class="result-item"><strong>Quiz:</strong><br>
                                        <pre>${response.quiz}</pre></div>`;

                        $('#quiz-result').append(quizHTML);
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
