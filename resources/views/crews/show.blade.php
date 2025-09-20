<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $crew->name }} - Crew Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-950 via-black to-gray-900 text-gray-100 selection:bg-indigo-600 selection:text-white">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-600 shadow-[0_0_25px_rgba(139,92,246,0.6)]">
        <div class="container mx-auto flex justify-between items-center p-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-wide drop-shadow-lg">
                    <a href="{{ route('home') }}" class="hover:text-pink-200 transition-colors">Crew Inventory</a>
                </h1>
                <p class="text-sm text-pink-200 tracking-wide uppercase">{{ $crew->name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('crews.index') }}" class="px-4 py-2 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-purple-500 hover:to-pink-600 text-sm font-semibold shadow-md hover:shadow-pink-500/40 transition">My Crews</a>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-{{ $userRole === 'owner' ? 'green' : 'blue' }}-500/20 text-{{ $userRole === 'owner' ? 'green' : 'blue' }}-300 border border-{{ $userRole === 'owner' ? 'green' : 'blue' }}-400/40 shadow-inner">{{ $userRole }}</span>
                <span class="text-sm text-gray-200">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in">
        <!-- Success + Error Messages -->
        @if(session('success'))
            <div class="col-span-3 bg-green-900/40 border border-green-500 text-green-200 px-4 py-3 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.5)] animate-pulse">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="col-span-3 bg-red-900/40 border border-red-500 text-red-200 px-4 py-3 rounded-xl shadow-[0_0_15px_rgba(239,68,68,0.5)]">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <!-- Left/Main Section -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Crew Info Card -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700 hover:shadow-[0_0_25px_rgba(139,92,246,0.5)] transition">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $crew->name }}</h2>
                        @if($crew->description)
                            <p class="text-gray-300 mt-2">{{ $crew->description }}</p>
                        @endif
                    </div>
                    @if($crew->isOwner(Auth::user()))
                        <div class="flex space-x-2">
                            <a href="{{ route('crews.edit', $crew) }}" class="px-3 py-1 rounded-lg text-sm font-semibold bg-gray-700 hover:bg-gray-600 hover:scale-105 transition">Edit</a>
                            <a href="{{ route('crews.roles', $crew) }}" class="px-3 py-1 rounded-lg text-sm font-semibold bg-purple-600 hover:bg-purple-700 hover:scale-105 transition">Manage Roles</a>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gradient-to-br from-blue-600/20 to-blue-800/30 p-4 rounded-xl hover:scale-105 transition">
                        <p class="text-3xl font-extrabold text-blue-400 drop-shadow-lg">{{ $crew->members->count() }}</p>
                        <p class="text-sm text-gray-300">Members</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-600/20 to-green-800/30 p-4 rounded-xl hover:scale-105 transition">
                        <p class="text-3xl font-extrabold text-green-400 drop-shadow-lg">{{ $crew->items->count() }}</p>
                        <p class="text-sm text-gray-300">Items</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-600/20 to-pink-800/30 p-4 rounded-xl hover:scale-105 transition">
                        <p class="text-sm font-mono font-bold text-purple-300">{{ $crew->invite_code }}</p>
                        <p class="text-sm text-gray-300">Invite Code</p>
                    </div>
                </div>

                @if($crew->canUserUpload(Auth::user()))
                    <div class="mt-6 pt-4 border-t border-gray-700">
                        <a href="{{ route('items.create') }}?crew={{ $crew->id }}" class="px-5 py-2 rounded-lg font-semibold bg-green-600 hover:bg-green-700 text-white shadow-lg hover:shadow-green-500/40 transition transform hover:scale-105 animate-pulse">Add Item to Crew</a>
                    </div>
                @endif
            </div>

            <!-- Items List -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold mb-6">Crew Items ({{ $crew->items->count() }})</h3>
                @if($crew->items->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($crew->items->take(6) as $item)
                            @php $canEdit = $crew->canUserEdit(Auth::user()) || $item->user_id === Auth::id(); @endphp
                            <div class="rounded-xl p-4 bg-gray-800/40 border border-gray-700 hover:shadow-[0_0_20px_rgba(99,102,241,0.5)] hover:border-indigo-500 transition transform hover:scale-[1.02] animate-slide-up">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-700 shadow-md">
                                        @if($item->image_path)
                                            <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}" class="w-full h-full object-cover hover:scale-110 transition">
                                        @else
                                            <div class="w-full h-full bg-gray-600"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-white"><a href="{{ route('items.show', $item) }}" class="hover:text-indigo-400">{{ $item->name }}</a></h4>
                                        <p class="text-sm text-gray-400">{{ $item->category }}</p>
                                        <p class="text-xs text-gray-500">by {{ $item->user->name }}</p>
                                        <div class="flex space-x-2 mt-3">
                                            <a href="{{ route('items.show', $item) }}" class="text-xs px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 hover:bg-indigo-500/40">View</a>
                                            @if($canEdit)
                                                <a href="{{ route('items.edit', $item) }}" class="text-xs px-3 py-1 rounded-full bg-green-500/20 text-green-300 hover:bg-green-500/40">Edit</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <p>No items in this crew yet</p>
                        @if($crew->canUserUpload(Auth::user()))
                            <a href="{{ route('items.create') }}?crew={{ $crew->id }}" class="text-indigo-400 hover:text-indigo-200">Add the first item</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Members -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700">
                <h3 class="text-lg font-bold mb-4">Members ({{ $crew->members->count() }})</h3>
                <div class="space-y-3">
                    @foreach($crew->members as $member)
                        @php $roleInfo = $crew->getMemberRoleInfo($member); @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-800/40 border border-gray-700 hover:border-indigo-500 hover:shadow-[0_0_20px_rgba(139,92,246,0.5)] transition animate-slide-up">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-medium shadow-lg
                                    @if($roleInfo['color'] === 'blue') bg-blue-500
                                    @elseif($roleInfo['color'] === 'green') bg-green-500
                                    @elseif($roleInfo['color'] === 'purple') bg-purple-500
                                    @elseif($roleInfo['color'] === 'red') bg-red-500
                                    @elseif($roleInfo['color'] === 'yellow') bg-yellow-500
                                    @elseif($roleInfo['color'] === 'pink') bg-pink-500
                                    @elseif($roleInfo['color'] === 'indigo') bg-indigo-500
                                    @else bg-gray-500 @endif">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-white">{{ $member->name }}</p>
                                    <p class="text-xs font-medium @if($roleInfo['type'] === 'custom') text-{{ $roleInfo['color'] }}-400 @else text-gray-400 @endif">{{ $roleInfo['name'] }}</p>
                                </div>
                            </div>
                            @if($crew->isOwner(Auth::user()) && !$crew->isOwner($member))
                                <div class="flex items-center space-x-2">
                                    <button onclick="openRoleModal({{ $member->id }}, '{{ $member->name }}')" class="text-xs px-2 py-1 rounded-lg bg-indigo-500/20 text-indigo-300 hover:bg-indigo-500/40 hover:scale-105 transition">Change</button>
                                    <form action="{{ route('crews.remove-member', [$crew, $member]) }}" method="POST" onsubmit="return confirm('Remove {{ $member->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-200 text-lg">×</button>
                                    </form>
                                </div>
                            @endif
                            @if($crew->isOwner($member))
                                <span class="text-xs px-2 py-1 rounded-full bg-green-500/20 text-green-300 font-bold">OWNER</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Invite -->
            @if($crew->isOwner(Auth::user()))
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold mb-4">Invite Members</h3>
                    <div class="p-3 rounded-xl bg-gray-800/40 border border-gray-700 mb-3 hover:border-indigo-500 transition">
                        <p class="text-sm text-gray-400 mb-1">Share this code:</p>
                        <p class="font-mono text-lg font-bold text-center py-2 bg-black/30 rounded-xl border border-gray-600">{{ $crew->invite_code }}</p>
                    </div>
                    <p class="text-xs text-gray-400">New members join as viewers. You can change their role later.</p>
                </div>
            @endif

            <!-- Role Permissions -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700">
                <h3 class="text-lg font-bold mb-4">Role Permissions</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Owner</span><span class="text-green-400">All permissions</span></div>
                    <div class="flex justify-between"><span>Editor</span><span class="text-blue-400">Upload & edit</span></div>
                    <div class="flex justify-between"><span>Uploader</span><span class="text-yellow-400">Upload only</span></div>
                    <div class="flex justify-between"><span>Viewer</span><span class="text-gray-400">View only</span></div>
                </div>
            </div>

            <!-- Leave Crew -->
            @if(!$crew->isOwner(Auth::user()))
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold mb-4 text-red-400">Leave Crew</h3>
                    <p class="text-sm text-gray-400 mb-4">You can leave this crew at any time.</p>
                    <form action="{{ route('crews.leave', $crew) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        <button type="submit" class="w-full py-2 rounded-lg font-semibold bg-red-600 hover:bg-red-700 text-white shadow-lg hover:shadow-red-500/40 transition">Leave Crew</button>
                    </form>
                </div>
            @endif
        </div>
    </main>

    <!-- Role Modal (REPLACE your existing modal with this block) -->
<div id="roleModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm">
  <div id="roleModalContent" class="bg-gray-900 rounded-2xl shadow-[0_0_25px_rgba(99,102,241,0.6)] w-full max-w-md p-6 border border-gray-700 transform scale-95 opacity-0 transition-all duration-300">
    <h2 class="text-xl font-bold mb-4 text-indigo-400">Change Role for <span id="memberName" class="text-white"></span></h2>

    <form id="roleAssignForm" method="POST">
      @csrf
      <!-- Role type -->
      <div class="mb-4">
        <label class="block font-semibold mb-2 text-gray-200">Role Type:</label>
        <div class="flex space-x-4">
          <label class="flex items-center space-x-2">
            <input id="roleTypeDefault" type="radio" name="role_type" value="default" checked class="form-radio text-indigo-500">
            <span class="text-gray-200">Default</span>
          </label>
          <label class="flex items-center space-x-2">
            <input id="roleTypeCustom" type="radio" name="role_type" value="custom" class="form-radio text-indigo-500">
            <span class="text-gray-200">Custom</span>
          </label>
        </div>
      </div>

      <!-- Default Role Section (visible for default) -->
      <div id="defaultRoleSection" class="mb-4">
        <label class="block font-semibold mb-2 text-gray-200">Choose Default Role:</label>
        <select id="defaultRoleSelect" name="role" class="w-full rounded-lg bg-gray-800 border border-gray-700 p-2 text-white">
          <option value="">-- Select default role --</option>
          <option value="editor">Editor</option>
          <option value="uploader">Uploader</option>
          <option value="viewer">Viewer</option>
        </select>
      </div>

      <!-- Custom Role Section (visible for custom) -->
      <div id="customRoleSection" class="hidden mb-4">
        <label for="customRoleSelect" class="block font-semibold mb-2 text-gray-200">Select Custom Role:</label>
        <select id="customRoleSelect" name="role_id" class="w-full rounded-lg bg-gray-800 border border-gray-700 p-2 text-white">
          <option value="">-- Choose a custom role --</option>
          @if(isset($customRoles))
            @foreach($customRoles as $role)
              <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          @endif
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-3 mt-6">
        <button
          type="button"
          onclick="closeRoleModal()"
          class="px-5 py-2 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold shadow-lg hover:scale-105 transition transform duration-200"
        >
          Cancel
        </button>

        <button
          type="submit"
          class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold shadow-lg hover:scale-105 transition transform duration-200"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Robust modal script (REPLACE existing modal script with this) -->
<script>
/* ---------- Modal open/close + toggle + safe submit ---------- */

function openRoleModal(memberId, memberName) {
  const modal = document.getElementById('roleModal');
  const content = document.getElementById('roleModalContent');
  const form = document.getElementById('roleAssignForm');

  // Set member name and form action (blade route replacement)
  document.getElementById('memberName').textContent = memberName;
  form.action = '{{ route("crews.assign-role", [$crew, "MEMBER_ID"]) }}'.replace('MEMBER_ID', memberId);

  // Show backdrop
  modal.classList.remove('hidden');

  // animate in
  requestAnimationFrame(() => {
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
  });

  // ensure correct section visible immediately
  applyRoleSectionVisibility();
}

function closeRoleModal() {
  const modal = document.getElementById('roleModal');
  const content = document.getElementById('roleModalContent');

  // animate out
  content.classList.remove('scale-100', 'opacity-100');
  content.classList.add('scale-95', 'opacity-0');

  // hide after animation
  setTimeout(() => modal.classList.add('hidden'), 300);
}

/* Show/hide sections (DO NOT disable inputs) */
function applyRoleSectionVisibility() {
  const selected = (document.querySelector('input[name="role_type"]:checked') || {}).value || 'default';
  const defaultSection = document.getElementById('defaultRoleSection');
  const customSection = document.getElementById('customRoleSection');

  if (selected === 'custom') {
    defaultSection.classList.add('hidden');
    customSection.classList.remove('hidden');
  } else {
    defaultSection.classList.remove('hidden');
    customSection.classList.add('hidden');
  }
}

/* DOM ready: attach listeners */
document.addEventListener('DOMContentLoaded', function() {
  // radio change handlers
  document.querySelectorAll('input[name="role_type"]').forEach(radio => {
    radio.addEventListener('change', applyRoleSectionVisibility);
  });

  // backdrop click closes modal when clicking outside content
  const modal = document.getElementById('roleModal');
  if (modal) {
    modal.addEventListener('click', function(e) {
      const content = document.getElementById('roleModalContent');
      if (!content.contains(e.target)) closeRoleModal();
    });
  }

  // Submit handler: make sure correct field is present and non-empty
  const form = document.getElementById('roleAssignForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      // Determine selected role_type:
      const roleType = (document.querySelector('input[name="role_type"]:checked') || {}).value || 'default';

      // Clean previous helper fields we might have inserted
      form.querySelectorAll('input[data-generated="1"]').forEach(n => n.remove());

      if (roleType === 'default') {
        // Read default select or radios (we use the select in this template)
        const defaultSelect = document.getElementById('defaultRoleSelect');
        const val = defaultSelect ? defaultSelect.value : '';

        if (!val) {
          e.preventDefault();
          alert('Please select a default role (editor, uploader, or viewer).');
          return false;
        }

        // If the visible default select exists and will submit normally, do nothing.
        // If not (rare), create a hidden fallback (keeps server-side validation happy).
        const existing = form.querySelector('[name="role"]:not([data-generated="1"])');
        if (!existing) {
          const h = document.createElement('input');
          h.type = 'hidden';
          h.name = 'role';
          h.value = val;
          h.setAttribute('data-generated', '1');
          form.appendChild(h);
        }

      } else { // custom
        const customSelect = document.getElementById('customRoleSelect');
        const val = customSelect ? customSelect.value : '';

        if (!val) {
          e.preventDefault();
          alert('Please select a custom role.');
          return false;
        }

        const existing = form.querySelector('[name="role_id"]:not([data-generated="1"])');
        if (!existing) {
          const h = document.createElement('input');
          h.type = 'hidden';
          h.name = 'role_id';
          h.value = val;
          h.setAttribute('data-generated', '1');
          form.appendChild(h);
        }
      }

      // Ensure role_type is present (radios normally submit it)
      if (!form.querySelector('[name="role_type"]')) {
        const selected = document.querySelector('input[name="role_type"]:checked');
        if (selected) {
          const h = document.createElement('input');
          h.type = 'hidden';
          h.name = 'role_type';
          h.value = selected.value;
          h.setAttribute('data-generated','1');
          form.appendChild(h);
        }
      }

      // allow submit
      return true;
    });
  }

  // initial visibility setup
  applyRoleSectionVisibility();
});
</script>



</body>
</html>




















