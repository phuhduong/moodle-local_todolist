@local_todolist @javascript
Feature: Manage to-do list tasks

  Scenario: Add a task
    Given I log in as "admin"
    And I navigate to "Plugins > To-Do List" in site administration
    And I should see "New task name"
    And I set the field "New task name" to "New Task"
    And I press "Add"
    Then I should see "New Task"

  Scenario: Rename a task
    Given I log in as "admin"
    And I navigate to "Plugins > To-Do List" in site administration
    And I set the field "New task name" to "Old name"
    And I press "Add"
    And I should see "Old name"
    And I press "Rename task Old name"
    And I set the field "Rename task Old name" to "New name"
    And I press "Rename task Old name"
    Then I should see "New name"
    And I should not see "Old name"

  Scenario: Delete a task
    Given I log in as "admin"
    And I navigate to "Plugins > To-Do List" in site administration
    And I set the field "New task name" to "New Task"
    And I press "Add"
    And I should see "New Task"
    And I press "Delete task New Task"
    Then I should not see "New Task"

  Scenario: Toggle a task
    Given I log in as "admin"
    And I navigate to "Plugins > To-Do List" in site administration
    And I set the field "New task name" to "New Task"
    And I press "Add"
    Then I should see "New Task"
    And I press "Toggle task New Task"
    Then I should see "Completed task New Task"
