<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест Dropdown</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-toggle {
            background: #8B5CF6;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 150px;
            z-index: 1000;
            display: none;
        }
        
        .dropdown-menu.show {
            display: block;
        }
        
        .dropdown-item {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        .dropdown-item:hover {
            background: #f5f5f5;
        }
        
        .dropdown-item.text-danger:hover {
            background: #fee;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <h1>Тест Dropdown Меню</h1>
    
    <div class="dropdown">
        <button class="dropdown-toggle" onclick="toggleDropdown(this)">
            <i class="fas fa-ellipsis-v"></i> Меню
        </button>
        <div class="dropdown-menu">
            <a href="#" class="dropdown-item">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="#" class="dropdown-item">
                <i class="fas fa-eye"></i> Просмотреть
            </a>
            <div style="height: 1px; background: #ddd; margin: 5px 0;"></div>
            <button class="dropdown-item text-danger" onclick="deleteItem()">
                <i class="fas fa-trash"></i> Удалить
            </button>
        </div>
    </div>
    
    <script>
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('show');
            console.log('Dropdown переключен:', dropdown.classList.contains('show'));
        }
        
        function deleteItem() {
            alert('Функция удаления!');
        }
        
        // Закрытие при клике вне dropdown
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
        
        console.log('Тест dropdown загружен');
    </script>
</body>
</html> 