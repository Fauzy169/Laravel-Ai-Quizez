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
            background-color: #f1f3f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 0;
        }

        .container {
            max-width: 800px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
            padding: 10px;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 1.1rem;
            transition: all 0.3s;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
            cursor: pointer;
        }

        #quiz-result {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .result-header {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #343a40;
        }

        .result-item {
            margin-bottom: 12px;
            font-size: 1rem;
            color: #6c757d;
        }

        pre {
            white-space: pre-wrap; 
            word-wrap: break-word;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 6px;
            font-size: 1rem;
            color: #495057;
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 2rem;
            }

            .card {
                padding: 20px;
            }

            .form-group input,
            button {
                padding: 10px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Quiz</h1>

        <div class="card">
            <div class="card-header">
                Quiz Settings
            </div>
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
        </div>

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
                        
                        let quizHTML = `<div class="result-header">Generated Quiz:</div>
                                        <div class="result-item"><strong>Quiz Details:</strong><br>
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
