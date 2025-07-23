# Spendy - Money Portfolio Tracking App

A modern, responsive web application built with Laravel, Vue 3, and PrimeVue for tracking annual financial goals and savings progress.

## Features

### 🎯 Financial Goal Management
- Create annual financial goals with start and target amounts
- Set optional expected progress amounts for tracking
- Categorize goals (savings, car, house, investment, emergency, vacation, etc.)
- Track progress with monthly updates
- Visual progress indicators and percentage calculations

### 📊 Progress Tracking
- Monthly progress updates with notes
- Real-time progress percentage calculations
- Comparison between actual and expected progress
- Visual progress bars and status indicators
- Historical progress tracking

### 📱 Responsive Design
- Fully responsive design for desktop, tablet, and mobile
- Modern UI with PrimeVue components
- Intuitive navigation and user experience
- Mobile-first approach

### 🔒 User Authentication
- Secure user authentication and authorization
- Users can only access their own goals
- Protected routes and data isolation

## Tech Stack

### Backend
- **Laravel 12** - PHP framework
- **MySQL/SQLite** - Database
- **Laravel Services & Actions** - Business logic separation
- **Pest** - Testing framework

### Frontend
- **Vue 3** - JavaScript framework with Composition API
- **PrimeVue** - UI component library
- **Pinia** - State management
- **Inertia.js** - SPA-like experience
- **Tailwind CSS** - Utility-first CSS framework

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm or yarn

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd spendy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Start Vite development server (in another terminal)**
   ```bash
   npm run dev
   ```

## Usage

### Creating a Financial Goal

1. Navigate to the Financial Goals page
2. Click "Add New Goal"
3. Fill in the goal details:
   - **Name**: Goal name (e.g., "Emergency Fund")
   - **Description**: Optional description
   - **Category**: Select from predefined categories
   - **Year**: Target year for the goal
   - **Starting Amount**: Current amount you have
   - **Target Amount**: Your goal amount
   - **Expected Amount**: Optional expected progress amount

### Tracking Progress

1. View your goal details
2. Click "Update Progress"
3. Enter the current amount for the month
4. Select the month and year
5. Add optional notes
6. Save the progress update

### Viewing Progress

- **Progress Overview**: See current amount, target, and percentage
- **Progress History**: View all monthly updates
- **Status Indicators**: See if you're ahead, behind, or on track
- **Year Filtering**: Filter goals by year

## Database Schema

### Financial Goals Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `name` - Goal name
- `description` - Optional description
- `start_amount` - Starting amount
- `target_amount` - Target amount
- `expected_amount` - Optional expected amount
- `year` - Target year
- `category` - Goal category
- `is_active` - Active status
- `created_at`, `updated_at` - Timestamps

### Goal Progress Table
- `id` - Primary key
- `financial_goal_id` - Foreign key to financial_goals table
- `current_amount` - Current amount for the month
- `month` - Month (1-12)
- `year` - Year
- `notes` - Optional notes
- `created_at`, `updated_at` - Timestamps

## Testing

Run the test suite:

```bash
php artisan test
```

Run specific test file:

```bash
php artisan test --filter=FinancialGoalTest
```

## API Endpoints

### Financial Goals
- `GET /financial-goals` - List all goals for the current year
- `GET /financial-goals?year=2025` - List goals for specific year
- `GET /financial-goals/create` - Show create goal form
- `POST /financial-goals` - Create new goal
- `GET /financial-goals/{id}` - Show goal details
- `GET /financial-goals/{id}/edit` - Show edit goal form
- `PUT /financial-goals/{id}` - Update goal
- `DELETE /financial-goals/{id}` - Delete goal

### Progress Updates
- `POST /financial-goals/{id}/progress` - Update goal progress

## Architecture

### Services
- `FinancialGoalService` - Handles business logic for goals and progress

### Actions
- `CreateFinancialGoal` - Validates and creates new goals
- `UpdateGoalProgress` - Validates and updates progress

### Models
- `FinancialGoal` - Goal model with relationships and computed attributes
- `GoalProgress` - Progress model with relationships
- `User` - User model with goals relationship

### Frontend Components
- **Index.vue** - Goals listing with year filtering
- **Create.vue** - Goal creation form
- **Show.vue** - Goal details with progress tracking
- **Edit.vue** - Goal editing form

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support and questions, please open an issue in the repository. 