<?php

$filename = 'todo_list.json';

function loadTodoList($filename)
{

  $json = file_get_contents($filename);

  $todoList = json_decode($json, true);

  return $todoList;
}

function saveTodoList($filename, $todoList)
{

  $json = json_encode($todoList);
  file_put_contents($filename, $json);
}

$todoList = loadTodoList($filename);

if (isset($_POST['save'])) {
  if (!empty($_POST['task']) && !empty($_POST['due_date']) && !empty($_POST['status']) && !empty($_POST['priority'])) {
    $task =  $_POST['task'];
    $dueDate = $_POST['due_date'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    $newTask = ['task' => $task, 'due_date' => $dueDate, 'status' => $status, 'priority' => $priority];

    $todoList[] = $newTask;

    saveTodoList($filename, $todoList);
  }
}

if (isset($_POST['updateTask'])) {
  if (isset($_POST['edit_index']) && isset($_POST['update_task']) && isset($_POST['update_due_date']) && isset($_POST['update_status']) && isset($_POST['update_priority'])) {
    $updatedIndex = $_POST['edit_index'];
    $updatedTask = $_POST['update_task'];
    $updatedDueDate = $_POST['update_due_date'];
    $updatedStatus = $_POST['update_status'];
    $updatedPriority = $_POST['update_priority'];


    if (isset($todoList[$updatedIndex])) {
      $todoList[$updatedIndex]['task'] = $updatedTask;
      $todoList[$updatedIndex]['due_date'] = $updatedDueDate;
      $todoList[$updatedIndex]['status'] = $updatedStatus;
      $todoList[$updatedIndex]['priority'] = $updatedPriority;

      saveTodoList($filename, $todoList);
    }
  }
}

if (isset($_POST['deleteTask'])) {

  $deleteIndex = $_POST['delete_index'];

  if (isset($todoList[$deleteIndex])) {

    unset($todoList[$deleteIndex]);
    $todoList = array_values($todoList);
    saveTodoList($filename, $todoList);
  }
}

function taskNumber()
{
  static $a = 1;
  echo $a;
  $a++;
}




?>






<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mjay Todo List App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
</head>

<body>
  <h1 class="text-center my-4" style="font-family:'Times New Roman', Times, serif;">Welcome To Mjay ToDo-List App</h1>
  <div class="w-50 m-auto">



    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
      Add Data
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Input Data</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST">
              <div class="mb-3">
                <label for="task" class="form-label">Task</label>
                <input type="text" class="form-control" id="task" name="task" placeholder="Enter Task to Add in ToDo">
              </div>

              <div class="mb-3">
                <label for="due-date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due-date" name="due_date">
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <class= form-control place holder="Open this select menu">
                  <select class="form-select" id="status" name="status">
                    <option selected>Open this select menu</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Blocked">Blocked</option>
                    <option value="MR">MR</option>
                    <option value="Ready for QA">Ready for QA</option>
                    <option value="Done">Done</option>
                  </select>
              </div>
              <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="low" name="priority" id="low">
                  <label class="form-check-label" for="low">
                    Low
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="medium" name="priority" id="medium">
                  <label class="form-check-label" for="medium">
                    Medium
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="high" name="priority" id="high" checked>
                  <label class="form-check-label" for="high">
                    High
                  </label>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="save">Save Task</button>
              </div>
          </div>
        </div>
      </div>
    </div>
    </form>
  </div>

  <div class="w-50 m-auto">
    <h1>My Lists</h1>

    <table class="table table-dark table-striped-columns">
      <thead class="text-center">
        <tr>
          <th scope="col">S/N</th>
          <th scope="col">Tasks</th>
          <th scope="col">Due Date</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
          <th scope="col">Priority</th>
          <th scope="col">Action</th>
        </tr>
      </thead>

      <?php if (!empty($todoList)): ?>

        <tbody class="text-center">
          <?php foreach ($todoList as $index => $task): ?>
            <tr>
              <td><?php taskNumber() ?></td>
              <td><?php echo $task['task']; ?></td>
              <td><?php echo $task['due_date']; ?></td>
              <td><?php echo $task['status']; ?></td>
              <td>
                <form method="POST">

                  <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                  <button type="submit" class="btn btn-danger" name="deleteTask">Delete</button>
                </form>
              </td>
              <td><?php echo $task['priority']; ?></td>

              <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $index; ?>">
                  Update</button>
              </td>
              <!-- Modal -->
              <div class="modal fade" id="staticBackdrop<?php echo $index; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?php echo $index; ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST">
                      <input type="hidden" name="edit_index" value="<?php echo $index; ?>">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="update_task" class="form-label">Task</label>
                          <input type="text" class="form-control" id="update_task" name="update_task" value="<?php echo $task['task']; ?>">
                        </div>
                        <div class="mb-3">
                          <label for="update_due_date" class="form-label">Due Date</label>
                          <input type="date" class="form-control" id="update_due_date" name="update_due_date" value="<?php echo $task['due_date']; ?>">
                        </div>
                        <label for="update-status" class="form-label">Status</label>
                        <class="form-control" place holder="Open this select menu">
                          <select class="form-select" id="update-status" name="update_status">
                            <option disabled selected>Open this select menu</option>
                            <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Blocked" <?php echo $task['status'] == 'Blocked' ? 'selected' : ''; ?>>Blocked</option>
                            <option value="MR" <?php echo $task['status'] == 'MR' ? 'selected' : ''; ?>>MR</option>
                            <option value="Ready for QA" <?php echo $task['status'] == 'Ready for QA' ? 'selected' : ''; ?>>Ready for QA</option>
                            <option value="Done" <?php echo $task['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                          </select>
                      </div>
                      <div class="mb-3">
                        <label for="update-priority" class="form-label">Priority</label>
                        <input type="text" class="form-control" id="update-priority" name="update_priority">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="low" name="update_priority" id="low">
                          <label class="form-check-label" for="low">
                            Low <?php echo $task['priority'] == 'low' ? 'selected' : ''; ?>
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="medium" name="update_priority" id="medium">
                          <label class="form-check-label" for="medium">
                            Medium <?php echo $task['status'] == 'Medium' ? 'selected' : ''; ?>
                          </label>
                        </div>
                        <input class="form-check-input" type="radio" value="high" name="update_priority" id="high" checked>
                        <label class="form-check-label" for="high">
                          High <?php echo $task['status'] == 'High' ? 'selected' : ''; ?>
                        </label>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="updateTask"> Update Task</button>
                      </div>
                  </div>
                </div>
              </div>
              </form>
            </tr>
          <?php endforeach; ?>
        </tbody>

      <?php else: ?>
        <p>No lists found.</p>
      <?php endif; ?>

    </table>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>