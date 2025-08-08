# Admin Quizzes Manager

This package provides an Admin Quizzes Manager for creating and managing quizzes, quiz questions, and quiz answers within your application.

---

## Features

- Create, edit, and delete quizzes
- Support for quiz metadata: course, difficulty (easy/medium/hard), passing score (%), time limit (minutes), status (active/inactive/draft)
- Manage quiz questions (multiple_choice, true_false, fill_in_blank, text)
- Manage quiz answers for each question, including marking correct answers
- Role-based access control for admins
- Select2 for better selects, CKEditor for rich text description

---

## Requirements

- PHP >= 8.2
- Laravel Framework >= 12.x

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/pavanraj92/admin-quizzes.git"
  }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/quizzes:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan vendor:publish --provider="admin\quizzes\QuizServiceProvider" --force
    ```

### 4. Optional Console Commands
    ```bash
    php artisan quizzes:publish --force     # publish with tag "quiz"
    php artisan quizzes:status              # check paths configured
    php artisan quizzes:debug               # basic config/view checks
    php artisan quizzes:test-views          # attempt to resolve key views
    ```
---

## Usage

1. **Create Quiz**: Define title, description, course, difficulty, passing score, time limit, and status.
2. **Add Questions**: For each quiz, create questions and set type, points, and explanation.
3. **Add Answers**: For each question, add answers and mark the correct one(s).
4. **Manage**: Edit/delete quizzes, questions, and answers as needed.

## Example Endpoints

Note: Admin routes are prefixed dynamically based on your `admins.website_slug` value and include `/admin`. For brevity, below shows paths without the dynamic prefix.

| Method | Endpoint                                         | Description                         |
|--------|--------------------------------------------------|-------------------------------------|
| GET    | `/quizzes`                                       | List all quizzes                    |
| POST   | `/quizzes`                                       | Create a new quiz                   |
| GET    | `/quizzes/{quiz}`                                | Get quiz details                    |
| PUT    | `/quizzes/{quiz}`                                | Update a quiz                       |
| DELETE | `/quizzes/{quiz}`                                | Delete a quiz                       |
| GET    | `/quizzes/{quiz}/questions`                      | List questions for a quiz           |
| POST   | `/quizzes/{quiz}/questions`                      | Add a question to a quiz            |
| GET    | `/quizzes/{quiz}/questions/{question}`           | Get question details                |
| PUT    | `/quizzes/{quiz}/questions/{question}`           | Update a question                   |
| DELETE | `/quizzes/{quiz}/questions/{question}`           | Delete a question                   |
| GET    | `/questions/{question}/answers`                  | List answers for a question         |
| POST   | `/questions/{question}/answers`                  | Add an answer to a question         |
| GET    | `/answers/{answer}/edit`                         | Edit answer (shallow route)         |
| PUT    | `/answers/{answer}`                              | Update an answer (shallow route)    |
| DELETE | `/answers/{answer}`                              | Delete an answer (shallow route)    |

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web', 'admin.auth'])->group(function () {
    // Admin Quiz routes here
});
```
---

## Database Tables

- `quizzes`
  - `id`, `user_id`, `course_id`, `title`, `description`, `difficulty`(`easy|medium|hard`), `passing_score`(0-100), `time_limit`(minutes), `status`(`active|inactive|draft`), timestamps, soft deletes
- `quiz_questions`
  - `id`, `quiz_id`, `question_text`, `question_type`(`multiple_choice|true_false|fill_in_blank|text`), `explanation`(nullable), `points`, timestamps
- `quiz_answers`
  - `id`, `question_id`, `answer_text`, `is_correct`(boolean), timestamps

---

## License

This package is open-sourced software licensed under the MIT license.
