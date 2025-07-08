/**
 * @module local_todolist/actions
 */

define([], function() {

    /**
     * Initialize the to-do list actions.
     */
    function init() {
        document.getElementById('addtask')?.addEventListener('click', () => {
            const name = document.getElementById('newtaskname').value.trim();
            if (!name) {
                return alert('Enter task name');
            }

            fetch('ajax.php?action=add', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({name})
            }).then(res => res.json())
                .then(() => location.reload())
                // eslint-disable-next-line no-alert
                .catch(() => alert('Failed to add task'));
        });

        document.querySelectorAll('.toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.closest('li').dataset.id;
                fetch('ajax.php?action=toggle', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({id})
                }).then(res => res.json())
                    .then(() => location.reload());
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
                    const id = li.dataset.id;
                    const name = input.value.trim();
                    if (!name) {
                        return;
                    }

                    fetch('ajax.php?action=rename', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({id, name})
                    }).then(res => res.json())
                        .then(() => location.reload());
                }
            });
        });

        document.querySelectorAll('.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.closest('li').dataset.id;
                fetch('ajax.php?action=delete', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({id})
                }).then(res => res.json())
                    .then(() => location.reload());
            });
        });
    }

    return {
        init: init
    };
});
