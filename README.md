# Lab Software Inventory

A PHP + MySQL web application, built on the AdminLTE 2 admin template, for keeping track of computer labs: the software installed in each lab, the weekly class timetable of each lab, and printable timetable spreadsheets.

Context: built for the IT department of an educational institution. Author: Nícollas Frazão. The interface is in Brazilian Portuguese.

## What it does

- **Lab overview** (`laboratorios.php`): one tile per lab for the selected semester (format `<semester>/<year>`, defaulting to the current one). Each tile takes the color of the course that has a class in that lab right now, based on the day of the week and the current time slot (morning 07:30-13:30 in three slots, afternoon 13:30-18:00 and evening 18:00-22:30 in two slots each). A legend lists the courses and their colors.
- **Add a lab** from a modal on the overview page.
- **Lab details** (`dadosLaboratorio.php`):
  - lab name, an "active" flag, and a multi-select (Select2) with the software installed in the lab for that semester;
  - the lab's weekly timetable, Monday to Saturday, morning/afternoon/evening, three slots each, showing course, subject and professor, with free slots marked as available.
- **Timetable spreadsheets** (`exibirPlanilha.php`), opened in a new tab and laid out for printing, filtered by semester and by department or course, in three layouts:
  1. classes per active lab for one period, Monday to Friday;
  2. the same, Monday to Saturday;
  3. every period on Saturday.

Class schedules and the software catalog are read from the database; the application has no screen to create them (see Notes).

## Tech stack

- PHP with PDO (MySQL)
- MySQL
- AdminLTE 2.3.8 (Bootstrap 3.3.6, jQuery 2.2.3, Select2), with Font Awesome 4.5.0 and Ionicons 2.0.1 loaded from a CDN
- A small XMLHttpRequest helper (`dist/js/ajax.js`) for the pages that load fragments from `dist/php/`

## Running locally

The database schema and data are not included.

1. Use PHP with the `pdo_mysql` extension and a MySQL server. The application files pass a PHP 7.4 syntax check, except `dist/php/atualizarDadosLaboratorio.php` (see Notes).
2. Create a database with the tables the code expects:
   - `tb_laboratorio` (`cd_laboratorio`, `nm_laboratorio`, `ic_ativado`)
   - `tb_software` (`cd_software`, `nm_software`)
   - `software_laboratorio` (`cd_software`, `cd_laboratorio`, `ds_semestre`)
   - `tb_aula` (`cd_aula`, `cd_laboratorio`, `cd_curso`, `cd_materia`, `cd_professor`, `dd_semana_aula`, `ds_periodo` = `M`/`T`/`N`, `cd_horario`, `ds_semestre`)
   - `tb_curso` (`cd_curso`, `nm_curso`, `ds_cor_hex`, `cd_secretaria`)
   - `tb_secretaria` (`cd_secretaria`, `nm_secretaria`)
   - `tb_materia` (`cd_materia`, `nm_materia`)
   - `tb_professor` (`cd_professor`, `nm_professor`)
3. Set the connection values in `dist/php/ConexaoPrincipal.php` (the committed credentials are placeholders).
4. Serve the folder, for example `php -S localhost:8000`, and open `laboratorios.php`.

## Project structure

```
laboratorios.php         Lab overview, add-lab modal, spreadsheet form
dadosLaboratorio.php     Lab details: software and weekly timetable
exibirPlanilha.php       Printable timetable spreadsheets
home.php, menu.php       AdminLTE starter page and shared header/sidebar
planilhas.php            Older version of the spreadsheet page (not linked)
dist/php/                Connection and endpoints/fragments (getLaboratorios, getAulas,
                         getLegendaCursos, adicionarLaboratorio, atualizarDadosLaboratorio)
dist/js/ajax.js          AJAX helper
bootstrap/, plugins/, build/, documentation/, pages/, index*.html, starter.html
                         AdminLTE 2.3.8 template files and demo pages (MIT)
```

## Notes

This is unfinished legacy code, kept as a portfolio sample:

- `dist/php/atualizarDadosLaboratorio.php` ends in the middle of its `UPDATE` statement and does not parse, so saving the lab details form (name, active flag, software list) does not work.
- The "Schedule" and "Edit" buttons in the timetable call `AbrirEdicaoHorario`, which is not defined; classes have to be entered directly in the database.
- The software count shown on each class cell is a fixed value.
- `home.php` and `menu.php` still carry AdminLTE placeholder content (demo user, notifications, tasks).
- `planilhas.php` mixes `mysqli_*` calls with the PDO connection and links to files that are not in the repository.
- Some queries use PDO prepared statements with bound parameters; others (`getAulas.php`, `exibirPlanilha.php`) interpolate request values into the SQL string.
- There is no authentication: the session checks are commented out.
- There are no automated tests.
