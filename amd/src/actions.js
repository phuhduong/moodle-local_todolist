/**
 * @module local_todolist/actions
 */

define(['core/ajax'], function(Ajax) {

    /**
     * Initialize the to-do list actions.
     */
    function init() {
        document.getElementById('addtask')?.addEventListener('click', () => {
            const name = document.getElementById('newtaskname').value.trim();
            if (!name) {
                return alert('Enter task name');
            }

            Ajax.call([{
                methodname: 'local_todolist_add_task',
                args: { name: name }
            }])[0].then(() => {
                location.reload();
            }).catch(() => {
                alert('Failed to add task');
            });
        });

        document.querySelectorAll('.toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.closest('li').dataset.id);

                Ajax.call([{
                    methodname: 'local_todolist_toggle_task',
                    args: { id: id }
                }])[0].then(() => {
                    location.reload();
                }).catch(() => {
                    alert('Failed to toggle task');
                });
            });
        });

        document.querySelectorAll('.rename').forEach(btn => {
            btn.addEventListener('click', () => {
                const li = btn.closest('li');
                const taskname = li.querySelector('.taskname');
                const input = li.querySelector('.renameinput');

                if (input.style.display === 'none') {
                    input.style.display = 'inline';
                    taskname.style.display = 'none';
                    input.focus();
                } else {
                    const id = parseInt(li.dataset.id);
                    const name = input.value.trim();
                    if (!name) {
                        return;
                    }

                    Ajax.call([{
                        methodname: 'local_todolist_rename_task',
                        args: { id: id, name: name }
                    }])[0].then(() => {
                        location.reload();
                    }).catch(() => {
                        alert('Failed to rename task');
                    });
                }
            });
        });

        document.querySelectorAll('.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.closest('li').dataset.id);

                Ajax.call([{
                    methodname: 'local_todolist_delete_task',
                    args: { id: id }
                }])[0].then(() => {
                    location.reload();
                }).catch(() => {
                    alert('Failed to delete task');
                });
            });
        });
    }

    return {
        init: init
    };
});