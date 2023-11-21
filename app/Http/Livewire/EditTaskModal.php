<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use App\Enums\TaskUnitTypes;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EditTaskModal extends Component
{
    public int $task_id;

    public Task $task;

    public function mount()
    {
        $this->task = Task::query()->find($this->task_id);
    }

    public function getTaskUnitTypesProperty()
    {
        return TaskUnitTypes::pluck('name', 'value');
    }

    public function editTask()
    {
        if ($this->task->isClean()) {
            $this->generateBrowserEvent(
                'no-change',
            );

            return;
        }

        $this->validate();

        if (!$this->task->save()) {
            $this->generateBrowserEvent(
                'failed-to-edit-task',
                'We could not update your task. Please try again'
            );

            return;
        }
        $this->generateBrowserEvent(
            'edit-task-successfully',
            'We successfully updated the task'
        );

        $this->emitTo(ViewProjectTasks::class, 'task-edited');
    }

    protected function generateBrowserEvent(string $event, string $message = '')
    {
        $this->dispatchBrowserEvent(
            event: $event,
            data: [
                'message' => $message,
                'modal' => '#edit-task-' . $this->task->id,
            ]
        );
    }

    public function render()
    {
        return view('livewire.edit-task-modal');
    }

    protected function rules(): array
    {
        return [
            'task.name' => Rule::when(
                condition: $this->task->isDirty('name'),
                rules:[
                    'required',
                    'string',
                    'min:3',
                    Rule::unique(
                        table: $this->task->getTable(),
                        column: 'name'
                    )
                ],
            ),
            'task.unit_type' => Rule::when(
                condition: $this->task->isDirty('name'),
                rules:[
                    'required',
                    new Enum(TaskUnitTypes::class)
                ],
            ),
            'task.target' => Rule::when(
                condition: $this->task->isDirty('name'),
                rules: [
                    'required','numeric'
                ],
            ),
        ];
    }
}
