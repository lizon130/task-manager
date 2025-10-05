<!-- resources/views/projects/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">My Projects</h3>
                        <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Project
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <div class="border rounded-lg p-4 shadow-sm">
                                <h4 class="font-semibold text-lg mb-2">{{ $project->name }}</h4>
                                <p class="text-gray-600 mb-4">{{ Str::limit($project->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                    <span class="text-sm text-gray-500">{{ $project->tasks_count ?? 0 }} tasks</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($projects->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">No projects found. <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800">Create your first project</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>