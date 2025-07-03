@local_todolist @javascript
Feature: Add a task to the to-do list
  In order to track my tasks
  As a logged-in Moodle user
  I want to add a new task from the interface

  Scenario: Add a task as admin
    Given I log in as "admin"
    And I am on "local/todolist/index.php"
    Then I should see "New task name"

    When I fill in "New task name" with "Buy milk"
    And I press "Add"
    Then I should see "Buy milk"
