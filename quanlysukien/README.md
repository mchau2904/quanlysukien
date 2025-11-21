php artisan migrate --path=/database/migrations/2025_10_30_094137_create_notifications_table.php
composer require maatwebsite/excel
attendance.php

-   các bước đổi ip mạng
    b1 vào cmd gõ ipconfig
    b2 tìm đến dòng
    IPv4 Address
    b3: vào file attendance.php
    thay ở allowed_ip_cidrs
    thành địa chỉ ip4/32
    ví dụ

               IPv4 Address . . . . . . . . . . . : 172.20.10.7
               thì thay vào allowed_ip_cidrs trong file attendance.php là 172.20.10.7/32

        php artisan migrate --path=/database/migrations/2025_10_31_103204_create_feedback_replies_table.php
        php artisan migrate --path=/database/migrations/2025_10_31_132526_add_is_recruiting_to_events_table.php
        php artisan migrate --path=/database/migrations/2025_10_31_142641_add_registration_deadline_to_events_table.php
        php artisan migrate --path=/database/migrations/2025_10_31_145312_create_notification_reads_table.php

    php artisan migrate --path=/database/migrations/2025_11_01_105537_add_image_url_to_events_table.php
    https://myaccount.google.com/apppasswords

-   sửa nội dung mail trong file
    nội dung mail khi tạo mới xong gửi mail ở file new_event.blade.php
    nội dung mail khi huy động xong gửi mail ở file recruit_event.blade.php
    php artisan storage:link

    php artisan migrate --path=/database/migrations/2025_11_05_010655_add_target_fields_to_events_table.php
