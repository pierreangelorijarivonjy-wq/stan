@auth
    <div x-data="chatBubble()" class="fixed bottom-6 right-6 z-[100] flex flex-col items-end"
        @keydown.escape.window="open = false; contextMenu.show = false; cancelEdit()">

        <!-- Toast Notification -->
        <div x-show="notification.show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-5 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-5 scale-95" @click="openChatFromNotification()"
            class="mb-4 mr-2 bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl shadow-2xl cursor-pointer hover:bg-white/20 transition-colors max-w-xs">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-full flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-comment-alt"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm" x-text="notification.title"></h4>
                    <p class="text-slate-300 text-xs truncate" x-text="notification.body"></p>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            class="mb-4 w-[420px] max-w-[calc(100vw-2rem)] h-[550px] max-h-[calc(100vh-8rem)] glass rounded-[2rem] overflow-hidden flex flex-col shadow-2xl border-white/10">

            <!-- Header -->
            <div class="p-5 bg-white/5 border-b border-white/10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <!-- Back Button -->
                    <template x-if="view !== 'list'">
                        <button @click="view = 'list'"
                            class="w-8 h-8 hover:bg-white/10 rounded-full flex items-center justify-center text-white transition-colors mr-1">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </template>

                    <!-- Icon/Avatar -->
                    <template x-if="view === 'list'">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20">
                            <i class="fas fa-comments"></i>
                        </div>
                    </template>

                    <!-- Title -->
                    <div>
                        <h3 class="text-white font-bold text-sm" x-text="getHeaderTitle()"></h3>
                        <div class="flex items-center gap-1.5" x-show="view === 'chat'">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"
                                x-text="typingUsers.length > 0 ? 'écrit...' : `${messages.length} messages`"></span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button x-show="view === 'list'" @click="view = 'new'"
                        class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button x-show="view === 'chat' && currentConversationType === 'group'" @click="view = 'info'"
                        class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
                        <i class="fas fa-info"></i>
                    </button>
                    <button @click="open = false" class="text-slate-400 hover:text-white transition-colors p-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-hidden relative bg-white/5">

                <!-- View: List -->
                <div x-show="view === 'list'" class="h-full overflow-y-auto custom-scrollbar p-2 space-y-2">
                    <!-- Global Chat Item -->
                    <div @click="selectGlobalChat()"
                        class="p-3 rounded-xl hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 border border-transparent hover:border-white/5">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-0.5">
                                <h4 class="text-white font-bold text-sm truncate">Discussion Globale</h4>
                                <span class="text-[10px] text-slate-400">Maintenant</span>
                            </div>
                            <p class="text-slate-400 text-xs truncate">Espace d'échange général</p>
                        </div>
                    </div>

                    <!-- Conversations List -->
                    <template x-for="conv in conversations" :key="conv.id">
                        <div @click="selectConversation(conv)"
                            class="p-3 rounded-xl hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 border border-transparent hover:border-white/5">
                            <div
                                class="w-12 h-12 bg-slate-700 rounded-full flex items-center justify-center text-white overflow-hidden">
                                <template x-if="conv.avatar">
                                    <img :src="conv.avatar" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!conv.avatar">
                                    <span class="font-bold text-lg" x-text="conv.name.charAt(0)"></span>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h4 class="text-white font-bold text-sm truncate" x-text="conv.name"></h4>
                                    <span class="text-[10px] text-slate-400" x-text="formatDate(conv.updated_at)"></span>
                                </div>
                                <p class="text-slate-400 text-xs truncate"
                                    :class="{'font-bold text-white': conv.unread_count > 0}"
                                    x-text="conv.last_message ? conv.last_message.content : 'Nouvelle conversation'"></p>
                            </div>
                            <div x-show="conv.unread_count > 0"
                                class="w-5 h-5 bg-premium-cyan rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-lg shadow-premium-cyan/20">
                                <span x-text="conv.unread_count"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- View: New Conversation -->
                <div x-show="view === 'new'" class="h-full flex flex-col p-4">
                    <!-- Tabs -->
                    <div class="flex p-1 bg-white/5 rounded-xl mb-4">
                        <button @click="newConversationType = 'private'"
                            :class="newConversationType === 'private' ? 'bg-white/10 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                            class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">
                            Privé
                        </button>
                        <button @click="newConversationType = 'group'"
                            :class="newConversationType === 'group' ? 'bg-white/10 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                            class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">
                            Groupe
                        </button>
                    </div>

                    <!-- Group Name Input (Only for Group) -->
                    <div x-show="newConversationType === 'group'" x-transition class="mb-4">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nom du
                            groupe</label>
                        <input type="text" x-model="newGroupName"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white placeholder-slate-500 focus:ring-1 focus:ring-premium-cyan focus:border-transparent outline-none"
                            placeholder="Ex: Projet Fin d'Année">
                    </div>

                    <!-- Search Users -->
                    <div class="relative mb-2">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsers"
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:ring-1 focus:ring-premium-cyan focus:border-transparent outline-none"
                            placeholder="Rechercher des participants...">
                    </div>

                    <!-- Selected Participants (Only for Group) -->
                    <div x-show="newConversationType === 'group' && selectedParticipants.length > 0"
                        class="flex flex-wrap gap-2 mb-4">
                        <template x-for="user in selectedParticipants" :key="user.id">
                            <div
                                class="flex items-center gap-1 bg-premium-cyan/20 text-premium-cyan px-2 py-1 rounded-lg text-xs font-bold border border-premium-cyan/30">
                                <span x-text="user.name"></span>
                                <button @click="toggleParticipant(user)" class="hover:text-white transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- User List -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar space-y-2">
                        <template x-for="user in searchResults" :key="user.id">
                            <div @click="handleUserSelect(user)"
                                class="p-3 rounded-xl hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 border border-transparent"
                                :class="isParticipantSelected(user) ? 'bg-white/5 border-premium-cyan/30' : ''">

                                <div class="relative">
                                    <div
                                        class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center text-white overflow-hidden">
                                        <template x-if="user.avatar_url">
                                            <img :src="user.avatar_url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!user.avatar_url">
                                            <span class="font-bold" x-text="user.name.charAt(0)"></span>
                                        </template>
                                    </div>
                                    <div x-show="isParticipantSelected(user)"
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-premium-cyan rounded-full flex items-center justify-center text-white text-[8px]">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-bold text-sm truncate" x-text="user.name"></h4>
                                    <p class="text-slate-400 text-xs truncate" x-text="user.email"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="searchQuery.length > 1 && searchResults.length === 0" class="text-center py-4">
                            <p class="text-slate-500 text-xs">Aucun utilisateur trouvé.</p>
                        </div>
                    </div>

                    <!-- Create Button (Only for Group) -->
                    <div x-show="newConversationType === 'group'" class="mt-4 pt-4 border-t border-white/10">
                        <button @click="createGroupConversation()"
                            :disabled="!newGroupName.trim() || selectedParticipants.length === 0"
                            class="w-full py-3 bg-premium-cyan text-white font-bold rounded-xl shadow-lg shadow-premium-cyan/20 hover:bg-premium-cyan/90 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            Créer le groupe
                        </button>
                    </div>
                </div>

                <!-- View: Chat -->
                <div x-show="view === 'chat'" class="h-full flex flex-col">
                    <!-- Messages List -->
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 custom-scrollbar" id="chat-bubble-messages"
                        @click="contextMenu.show = false" @scroll="handleScroll">

                        <!-- Loading Spinner -->
                        <div x-show="isLoadingMore" class="flex justify-center py-2">
                            <div class="w-4 h-4 border-2 border-white/20 border-t-premium-cyan rounded-full animate-spin">
                            </div>
                        </div>

                        <!-- Context Menu -->
                        <div x-show="contextMenu.show" x-cloak :style="`top: ${contextMenu.y}px; left: ${contextMenu.x}px;`"
                            class="absolute z-50 w-64 glass rounded-2xl shadow-2xl border border-white/20 overflow-hidden py-1"
                            @click.stop>

                            <!-- Emoji Picker -->
                            <div class="px-2 py-2 flex justify-between gap-1 border-b border-white/10">
                                <template x-for="emoji in ['👍', '❤️', '😂', '😮', '😢', '😡']">
                                    <button @click="reactToMessage(contextMenu.messageId, emoji)"
                                        class="w-8 h-8 rounded-full hover:bg-white/10 flex items-center justify-center text-lg transition-colors"
                                        :class="hasReacted(contextMenu.messageId, emoji) ? 'bg-white/20' : ''">
                                        <span x-text="emoji"></span>
                                    </button>
                                </template>
                            </div>

                            <button @click="handleReply(contextMenu.messageId)"
                                class="w-full px-4 py-2.5 text-left text-xs text-white hover:bg-white/10 flex items-center gap-3 transition-colors">
                                <i class="fas fa-reply w-4 text-slate-400"></i>
                                <span>Répondre</span>
                            </button>

                            <template x-if="isMyMessage(contextMenu.messageId) && isEditable(contextMenu.messageId)">
                                <button @click="handleEdit(contextMenu.messageId)"
                                    class="w-full px-4 py-2.5 text-left text-xs text-white hover:bg-white/10 flex items-center gap-3 transition-colors">
                                    <i class="fas fa-pen w-4 text-slate-400"></i>
                                    <span>Modifier</span>
                                </button>
                            </template>

                            <template x-if="!isMyMessage(contextMenu.messageId)">
                                <button @click="reportMessage(contextMenu.messageId)"
                                    class="w-full px-4 py-2.5 text-left text-xs text-amber-400 hover:bg-white/10 flex items-center gap-3 transition-colors">
                                    <i class="fas fa-flag w-4"></i>
                                    <span>Signaler</span>
                                </button>
                            </template>

                            <div class="h-px bg-white/10 my-1"></div>

                            <template x-if="isMyMessage(contextMenu.messageId)">
                                <button @click="deleteMessage(contextMenu.messageId, 'all')"
                                    class="w-full px-4 py-2.5 text-left text-xs text-rose-400 hover:bg-rose-500/10 flex items-center gap-3 transition-colors">
                                    <i class="fas fa-trash-alt w-4"></i>
                                    <span>Retirer (pour tout le monde)</span>
                                </button>
                            </template>

                            <button @click="deleteMessage(contextMenu.messageId, 'me')"
                                class="w-full px-4 py-2.5 text-left text-xs text-slate-300 hover:bg-white/10 flex items-center gap-3 transition-colors">
                                <i class="fas fa-eye-slash w-4"></i>
                                <span>Supprimer pour moi</span>
                            </button>
                        </div>

                        <!-- Messages Loop -->
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex" :class="msg.sender_id == {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[85%] flex flex-col"
                                    :class="msg.sender_id == {{ Auth::id() }} ? 'items-end' : 'items-start'">
                                    <!-- Sender Name -->
                                    <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-1 px-1"
                                        x-show="msg.sender_id != {{ Auth::id() }}" x-text="msg.sender.name"></span>

                                    <!-- Message Bubble -->
                                    <div class="relative group" @contextmenu.prevent="showContextMenu($event, msg.id)"
                                        @dblclick="reactToMessage(msg.id, '❤️'); showHeartAnimation($event)"
                                        @touchstart="startLongPress($event, msg.id)" @touchend="clearLongPress()"
                                        @touchmove="clearLongPress()" style="touch-action: manipulation;">

                                        <!-- Quoted Message -->
                                        <template x-if="msg.reply_to">
                                            <div
                                                class="mb-1 px-3 py-1.5 rounded-lg bg-black/20 text-[10px] border-l-2 border-white/30 cursor-pointer opacity-80 hover:opacity-100 transition-opacity">
                                                <p class="font-bold text-white/70" x-text="msg.reply_to.sender.name"></p>
                                                <p class="text-white/50 truncate" x-text="msg.reply_to.content"></p>
                                            </div>
                                        </template>

                                        <div class="px-4 py-2.5 rounded-2xl text-xs leading-relaxed transition-all" :class="[
                                                                msg.sender_id == {{ Auth::id() }} ? 'bg-premium-cyan text-white rounded-tr-none shadow-lg shadow-premium-cyan/10' : 'bg-white/10 text-slate-200 border border-white/10 rounded-tl-none',
                                                                msg.is_deleted ? 'italic opacity-50' : ''
                                                             ]">

                                            <!-- Attachments -->
                                            <template x-if="msg.attachment_path && !msg.is_deleted">
                                                <div class="mb-2">
                                                    <template x-if="msg.attachment_type === 'image'">
                                                        <div class="relative group/image inline-block">
                                                            <img :src="msg.attachment_path"
                                                                class="max-w-[200px] rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                                @click="window.open(msg.attachment_path, '_blank')">
                                                            <a :href="msg.attachment_path" download
                                                                class="absolute bottom-2 right-2 w-8 h-8 bg-black/50 hover:bg-black/70 rounded-full flex items-center justify-center text-white opacity-0 group-hover/image:opacity-100 transition-opacity backdrop-blur-sm"
                                                                @click.stop>
                                                                <i class="fas fa-download text-xs"></i>
                                                            </a>
                                                        </div>
                                                    </template>
                                                    <template x-if="msg.attachment_type === 'audio'">
                                                        <div
                                                            class="flex items-center gap-2 bg-black/20 p-2 rounded-xl min-w-[200px]">
                                                            <audio controls
                                                                class="w-full h-8 [&::-webkit-media-controls-panel]:bg-transparent [&::-webkit-media-controls-enclosure]:bg-transparent">
                                                                <source :src="msg.attachment_path" type="audio/mpeg">
                                                            </audio>
                                                        </div>
                                                    </template>
                                                    <template x-if="msg.attachment_type === 'file'">
                                                        <a :href="msg.attachment_path" download
                                                            class="flex items-center gap-3 p-2.5 bg-black/20 rounded-xl hover:bg-black/30 transition-all group/file border border-white/5 hover:border-white/10 max-w-[220px]">
                                                            <div
                                                                class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                                                <i class="fas fa-file-lines text-xl text-slate-200"></i>
                                                            </div>
                                                            <div class="flex-1 min-w-0 text-left">
                                                                <p class="text-xs font-bold text-slate-200 truncate">Fichier
                                                                    joint</p>
                                                                <p
                                                                    class="text-[10px] text-premium-cyan group-hover/file:underline">
                                                                    Télécharger</p>
                                                            </div>
                                                            <i
                                                                class="fas fa-cloud-download-alt text-slate-400 group-hover/file:text-white transition-colors"></i>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>

                                            <span x-text="msg.content"></span>
                                            <span x-show="msg.edited_at" class="text-[9px] opacity-60 ml-1">(modifié)</span>
                                        </div>

                                        <!-- Reactions -->
                                        <div x-show="msg.reactions_grouped && Object.keys(msg.reactions_grouped).length > 0"
                                            class="absolute -bottom-3 right-0 flex gap-1">
                                            <template x-for="(data, emoji) in msg.reactions_grouped" :key="emoji">
                                                <div class="bg-premium-dark border border-white/10 rounded-full px-1.5 py-0.5 flex items-center gap-1 shadow-lg cursor-pointer hover:scale-110 transition-transform"
                                                    :class="data.reacted_by_me ? 'border-premium-cyan/50 bg-premium-cyan/10' : ''"
                                                    @click="reactToMessage(msg.id, emoji)">
                                                    <span class="text-[10px]" x-text="emoji"></span>
                                                    <span class="text-[8px] font-bold text-white"
                                                        x-text="data.count"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 mt-1 px-1">
                                        <span class="text-[8px] text-slate-600" x-text="formatTime(msg.created_at)"></span>
                                        <!-- Read Status (Only for my messages) -->
                                        <template x-if="msg.sender_id == {{ Auth::id() }} && currentConversationId">
                                            <span class="text-[8px]">
                                                <template x-if="isMessageRead(msg)">
                                                    <i class="fas fa-check-double text-premium-cyan"></i>
                                                </template>
                                                <template x-if="!isMessageRead(msg)">
                                                    <i class="fas fa-check text-slate-500"></i>
                                                </template>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                        </template>
                    </div>

                    <!-- New Message Floating Button -->
                    <div x-show="showNewMessageButton" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-5"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-5"
                        class="absolute bottom-20 left-1/2 -translate-x-1/2 z-40">
                        <button @click="scrollToBottom(); showNewMessageButton = false"
                            class="bg-premium-cyan text-white px-4 py-2 rounded-full shadow-lg shadow-premium-cyan/30 flex items-center gap-2 text-xs font-bold hover:scale-105 transition-transform">
                            <i class="fas fa-arrow-down animate-bounce"></i>
                            <span>Nouveaux messages</span>
                        </button>
                    </div>

                    <!-- Notification Sound -->
                    <audio x-ref="notificationSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"
                        preload="auto"></audio>

                    <!-- Input -->
                    <div class="p-4 bg-white/5 border-t border-white/10">
                        <!-- Typing Indicator -->
                        <div x-show="typingUsers.length > 0" x-transition
                            class="mb-2 px-2 text-[10px] text-slate-400 italic flex items-center gap-2">
                            <div class="flex gap-0.5">
                                <div class="w-1 h-1 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0ms">
                                </div>
                                <div class="w-1 h-1 bg-slate-400 rounded-full animate-bounce"
                                    style="animation-delay: 150ms"></div>
                                <div class="w-1 h-1 bg-slate-400 rounded-full animate-bounce"
                                    style="animation-delay: 300ms"></div>
                            </div>
                            <span x-text="getTypingText()"></span>
                        </div>

                        <!-- Reply/Edit Preview -->
                        <div x-show="replyToMessage || editingMessage" x-transition
                            class="mb-2 p-2 bg-white/10 rounded-lg border-l-4 flex justify-between items-center"
                            :class="editingMessage ? 'border-amber-500' : 'border-premium-cyan'">
                            <div class="flex-1 min-w-0">
                                <template x-if="replyToMessage">
                                    <div>
                                        <p class="text-[10px] text-premium-cyan font-bold mb-0.5"
                                            x-text="replyToMessage?.sender?.name"></p>
                                        <p class="text-xs text-slate-300 truncate" x-text="replyToMessage?.content"></p>
                                    </div>
                                </template>
                                <template x-if="editingMessage">
                                    <div>
                                        <p class="text-[10px] text-amber-500 font-bold mb-0.5">Modification du message</p>
                                        <p class="text-xs text-slate-300 truncate" x-text="editingMessage?.content"></p>
                                    </div>
                                </template>
                            </div>
                            <button @click="cancelEdit()" class="text-slate-400 hover:text-white p-1">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Attachment Preview -->
                        <div x-show="attachmentPreview" x-transition
                            class="mb-2 p-2 bg-white/10 rounded-lg border border-white/10 flex justify-between items-center">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <template x-if="attachmentType === 'image'">
                                    <img :src="attachmentPreview" class="w-8 h-8 object-cover rounded">
                                </template>
                                <template x-if="attachmentType === 'audio'">
                                    <i class="fas fa-microphone text-premium-cyan"></i>
                                </template>
                                <template x-if="attachmentType === 'file'">
                                    <i class="fas fa-file text-slate-300"></i>
                                </template>
                                <span class="text-xs text-slate-300 truncate" x-text="attachmentName"></span>
                            </div>
                            <button @click="clearAttachment()" class="text-slate-400 hover:text-white p-1">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
                            <!-- Attachment Button -->
                            <button type="button" @click="$refs.fileInput.click()"
                                class="p-2.5 text-slate-400 hover:text-white transition-colors">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="file" x-ref="fileInput" class="hidden" @change="handleFileSelect">

                            <div class="flex-1 relative">
                                <textarea x-model="newMessage" rows="1" @keydown.enter.prevent="sendMessage"
                                    @input="sendTypingEvent"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-premium-cyan focus:border-transparent outline-none transition-all resize-none custom-scrollbar"
                                    placeholder="Votre message..."></textarea>
                            </div>

                            <!-- Voice Record Button (Show if no text) -->
                            <button type="button" x-show="!newMessage.trim() && !attachmentPreview && !isRecording"
                                @click="startRecording()"
                                class="w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-xl flex items-center justify-center transition-all shrink-0">
                                <i class="fas fa-microphone"></i>
                            </button>

                            <!-- Stop Recording Button -->
                            <button type="button" x-show="isRecording" @click="stopRecording()"
                                class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center animate-pulse shrink-0">
                                <i class="fas fa-stop"></i>
                            </button>

                            <!-- Send Button (Show if text or attachment) -->
                            <button type="submit" x-show="newMessage.trim() || attachmentPreview"
                                class="w-10 h-10 bg-premium-cyan hover:bg-premium-cyan/80 text-white rounded-xl flex items-center justify-center shadow-lg shadow-premium-cyan/20 transition-all shrink-0">
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- View: Info -->
                <div x-show="view === 'info'" class="h-full flex flex-col p-4">
                    <h4 class="text-white font-bold text-lg mb-4" x-text="currentConversationName"></h4>

                    <!-- Participants List -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar space-y-2 mb-4">
                        <h5 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Participants (<span
                                x-text="currentConversationParticipants.length"></span>)</h5>
                        <template x-for="participant in currentConversationParticipants" :key="participant.id">
                            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition-colors">
                                <div
                                    class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center text-white overflow-hidden">
                                    <template x-if="participant.avatar_url">
                                        <img :src="participant.avatar_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!participant.avatar_url">
                                        <span class="font-bold" x-text="participant.name.charAt(0)"></span>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-bold text-sm truncate" x-text="participant.name"></h4>
                                    <p class="text-slate-400 text-xs truncate" x-text="participant.email"></p>
                                </div>
                                <span x-show="participant.is_admin"
                                    class="text-[10px] text-premium-cyan font-bold">Admin</span>
                                <button x-show="isAdmin && participant.id !== {{ Auth::id() }}"
                                    @click="removeParticipant(participant)"
                                    class="text-rose-400 hover:text-rose-500 transition-colors p-1">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Add Participant -->
                    <div x-show="isAdmin" class="mb-4">
                        <h5 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Ajouter un
                            participant</h5>
                        <div class="relative mb-2">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsersForAdd"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:ring-1 focus:ring-premium-cyan focus:border-transparent outline-none"
                                placeholder="Rechercher des utilisateurs...">
                        </div>
                        <div class="max-h-32 overflow-y-auto custom-scrollbar space-y-1">
                            <template x-for="user in searchResults" :key="user.id">
                                <div @click="addParticipant(user)"
                                    class="p-2 rounded-xl hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 border border-transparent">
                                    <div
                                        class="w-8 h-8 bg-slate-700 rounded-full flex items-center justify-center text-white overflow-hidden">
                                        <template x-if="user.avatar_url">
                                            <img :src="user.avatar_url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!user.avatar_url">
                                            <span class="font-bold text-sm" x-text="user.name.charAt(0)"></span>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-white font-bold text-xs truncate" x-text="user.name"></h4>
                                    </div>
                                    <i class="fas fa-plus text-premium-cyan"></i>
                                </div>
                            </template>
                            <div x-show="searchQuery.length > 1 && searchResults.length === 0" class="text-center py-2">
                                <p class="text-slate-500 text-xs">Aucun utilisateur trouvé.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Group Button -->
                    <button @click="leaveGroup()"
                        class="w-full py-3 bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all mt-auto">
                        Quitter le groupe
                    </button>
                </div>
            </div>
        </div>

        <!-- Bubble Button -->
        <button @click="open = !open"
            class="w-16 h-16 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-premium-cyan/30 transition-all hover:scale-110 active:scale-95 group relative">
            <i class="fas fa-comment-dots text-2xl group-hover:hidden" x-show="!open"></i>
            <i class="fas fa-times text-2xl hidden group-hover:block" x-show="open"></i>
            <i class="fas fa-chevron-down text-2xl hidden group-hover:block" x-show="!open"></i>

            <!-- Unread Badge -->
            <span
                class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 border-2 border-premium-dark rounded-full flex items-center justify-center text-[10px] font-black"
                x-show="totalUnread > 0" x-text="totalUnread"></span>
        </button>
    </div>

    <script>
        function chatBubble() {
            return {
                open: false,
                view: 'list', // 'list', 'chat', 'new', 'info'
                conversations: [],
                currentConversationId: null,
                currentConversationName: '',
                currentConversationType: 'private',
                currentConversationParticipants: [],
                isAdmin: false,
                messages: [],
                nextPageUrl: null,
                isLoadingMore: false,
                newMessage: '',
                newMessage: '',
                searchQuery: '',
                searchResults: [],

                // New Conversation State
                newConversationType: 'private', // 'private', 'group'
                newGroupName: '',
                selectedParticipants: [],

                replyToMessage: null,
                editingMessage: null,
                echoChannel: null,
                typingUsers: [],
                typingTimeout: null,
                contextMenu: {
                    show: false,
                    x: 0,
                    y: 0,
                    messageId: null
                },
                notification: {
                    show: false,
                    title: '',
                    body: '',
                    timeout: null
                },
                longPressTimer: null,
                pollingInterval: null,

                // Attachments & Voice
                attachmentFile: null,
                attachmentPreview: null,
                attachmentType: null, // 'image', 'audio', 'file'
                attachmentName: '',
                isRecording: false,
                mediaRecorder: null,
                audioChunks: [],

                showNewMessageButton: false,
                isAtBottom: true,

                handleScroll(e) {
                    const el = e.target;
                    // Check if we are near bottom (within 50px)
                    this.isAtBottom = (el.scrollHeight - el.scrollTop - el.clientHeight) < 50;

                    if (this.isAtBottom) {
                        this.showNewMessageButton = false;
                    }
                },

                get totalUnread() {
                    return this.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
                },

                init() {
                    this.fetchConversations();
                    this.subscribeToChannel();

                    // Keep a slow poll for conversations list updates
                    this.pollingInterval = setInterval(() => {
                        if (this.open && this.view === 'list') {
                            this.fetchConversations();
                        }
                    }, 10000);
                },

                getHeaderTitle() {
                    if (this.view === 'list') return 'Discussions';
                    if (this.view === 'new') return 'Nouvelle discussion';
                    if (this.view === 'info') return 'Infos du groupe';
                    return this.currentConversationName;
                },

                subscribeToChannel() {
                    if (this.echoChannel) {
                        window.Echo.leave(this.echoChannel);
                        this.echoChannel = null;
                    }

                    if (this.view === 'chat') {
                        if (this.currentConversationId) {
                            this.echoChannel = `chat.conversation.${this.currentConversationId}`;
                        } else {
                            this.echoChannel = 'chat.global';
                        }

                        const channel = window.Echo.private(this.echoChannel);

                        channel.listen('MessageSent', (e) => {
                            if (this.view === 'chat') {
                                if ((!this.currentConversationId && !e.message.conversation_id) ||
                                    (this.currentConversationId == e.message.conversation_id)) {
                                    this.messages.push(e.message);
                                    // Play sound if message is not from me
                                            if (e.message.sender_id != {{ Auth::id() }}) {
                                                this.$refs.notificationSound.play().catch(e => console.log('Audio play failed', e));
                                            }

                                            // Check if we should scroll or show button
                                            if (this.isAtBottom) {
                                                this.scrollToBottom();
                                                if (this.open) this.markAsRead();
                                            } else {
                                                if (e.message.sender_id != {{ Auth::id() }}) {
                                                    this.showNewMessageButton = true;
                                                } else {
                                                    this.scrollToBottom(); // Always scroll for my own messages
                                                }
                                            }
                                        }
                                    } else {
                                        this.showNotification(e.message);
                                        this.$refs.notificationSound.play().catch(e => console.log('Audio play failed', e));
                                    }
                                    this.fetchConversations();
                                })
                                    .listenForWhisper('typing', (e) => {
                                        if (!this.typingUsers.find(u => u.name === e.name)) {
                                            this.typingUsers.push(e);
                                            setTimeout(() => {
                                                this.typingUsers = this.typingUsers.filter(u => u.name !== e.name);
                                            }, 3000);
                                        }
                                    })
                                    .listen('MessageRead', (e) => {
                                        // Update read status for my messages
                                        this.messages.forEach(msg => {
                                            if (msg.sender_id == {{ Auth::id() }} && msg.created_at <= e.read_at) {
                                                // Force update UI (Alpine might not detect deep change)
                                                // We can use a helper or just rely on re-render
                                            }
                                        });
                                    })
                                    .listen('MessageReactionAdded', (e) => {
                                        const msg = this.messages.find(m => m.id === e.messageId);
                                        if (msg) {
                                            msg.reactions_grouped = e.reactions;
                                        }
                                    })
                                    .listen('MessageUpdated', (e) => {
                                        const index = this.messages.findIndex(m => m.id === e.message.id);
                                        if (index !== -1) {
                                            // Preserve sender info if not present in event payload (usually it is)
                                            const sender = this.messages[index].sender;
                                            this.messages[index] = { ...e.message, sender: sender };
                                        }
                                    });
                            }
                        },

                        async markAsRead() {
                            if (!this.currentConversationId) return;
                            try {
                                await fetch(`/conversations/${this.currentConversationId}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });
                            } catch (error) { console.error('Error marking as read:', error); }
                        },

                        isMessageRead(msg) {
                            // Logic to check if message is read based on conversation participants' last_read_at
                            // This requires passing participants data to frontend.
                            // For now, simplify: if message is older than 1 min, assume read (placeholder)
                            // Real implementation needs participants data.
                            return true;
                        },

                        showNotification(message) {
                            if (!this.open) {
                                this.notification.title = message.sender.name;
                                this.notification.body = message.content;
                                this.notification.show = true;

                                if (this.notification.timeout) clearTimeout(this.notification.timeout);

                                this.notification.timeout = setTimeout(() => {
                                    this.notification.show = false;
                                }, 5000);
                            }
                        },

                        openChatFromNotification() {
                            this.notification.show = false;
                            this.open = true;
                            // Logic to open specific conversation could be added here if we had conversation ID in message event
                            // For now, just open the chat window
                            this.view = 'list';
                        },

                        sendTypingEvent() {
                            if (this.echoChannel) {
                                if (this.typingTimeout) clearTimeout(this.typingTimeout);

                                window.Echo.private(this.echoChannel)
                                    .whisper('typing', {
                                        name: '{{ Auth::user()->name }}'
                                    });

                                // Throttle sending events
                                this.typingTimeout = setTimeout(() => {
                                    // Stopped typing
                                }, 1000);
                            }
                        },

                        getTypingText() {
                            if (this.typingUsers.length === 0) return '';
                            if (this.typingUsers.length === 1) return `${this.typingUsers[0].name} est en train d'écrire...`;
                            return `${this.typingUsers.length} personnes écrivent...`;
                        },

                        async fetchConversations() {
                            try {
                                const response = await fetch('/conversations');
                                if (response.ok) {
                                    this.conversations = await response.json();
                                }
                            } catch (error) {
                                console.error('Error fetching conversations:', error);
                            }
                        },

                        async searchUsers() {
                            if (this.searchQuery.length < 2) {
                                this.searchResults = [];
                                return;
                            }
                            try {
                                const response = await fetch(`/users/search?query=${this.searchQuery}`);
                                if (response.ok) {
                                    this.searchResults = await response.json();
                                }
                            } catch (error) {
                                console.error('Error searching users:', error);
                            }
                        },

                        async searchUsersForAdd() {
                            if (this.searchQuery.length < 2) {
                                this.searchResults = [];
                                return;
                            }
                            try {
                                const response = await fetch(`/users/search?query=${this.searchQuery}`);
                                if (response.ok) {
                                    const allUsers = await response.json();
                                    // Filter out users already in the current conversation
                                    this.searchResults = allUsers.filter(user =>
                                        !this.currentConversationParticipants.some(p => p.id === user.id)
                                    );
                                }
                            } catch (error) {
                                console.error('Error searching users for add:', error);
                            }
                        },

                        selectGlobalChat() {
                            this.currentConversationId = null;
                            this.currentConversationName = 'Discussion Globale';
                            this.currentConversationType = 'global'; // Assuming 'global' type for global chat
                            this.currentConversationParticipants = [];
                            this.isAdmin = false;
                            this.view = 'chat';
                            this.messages = [];
                            this.fetchMessages();
                            this.subscribeToChannel();
                        },

                        selectConversation(conv) {
                            this.currentConversationId = conv.id;
                            this.currentConversationName = conv.name;
                            this.currentConversationType = conv.type;
                            this.currentConversationParticipants = conv.participants || [];
                            this.isAdmin = conv.is_admin || false;
                            this.view = 'chat';
                            this.messages = [];
                            this.fetchMessages();
                            this.subscribeToChannel();
                            this.markAsRead();
                        },

                        handleUserSelect(user) {
                            if (this.newConversationType === 'private') {
                                this.startConversation(user);
                            } else {
                                this.toggleParticipant(user);
                            }
                        },

                        toggleParticipant(user) {
                            const index = this.selectedParticipants.findIndex(p => p.id === user.id);
                            if (index === -1) {
                                this.selectedParticipants.push(user);
                            } else {
                                this.selectedParticipants.splice(index, 1);
                            }
                        },

                        isParticipantSelected(user) {
                            return this.selectedParticipants.some(p => p.id === user.id);
                        },

                        async startConversation(user) {
                            try {
                                const response = await fetch('/conversations', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        type: 'private',
                                        participants: [user.id]
                                    })
                                });

                                if (response.ok) {
                                    const conv = await response.json();
                                    this.selectConversation(conv);
                                }
                            } catch (error) {
                                console.error('Error starting conversation:', error);
                            }
                        },

                        async addParticipant(user) {
                            try {
                                const response = await fetch(`/conversations/${this.currentConversationId}/participants`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ user_id: user.id })
                                });

                                if (response.ok) {
                                    // Refresh conversation data to update participants list
                                    this.fetchConversations();
                                    // Ideally we should just fetch this conversation details, but this works for now
                                    // Or manually add to list if we want instant UI update
                                    this.currentConversationParticipants.push({
                                        id: user.id,
                                        name: user.name,
                                        avatar_url: user.avatar_url,
                                        is_admin: false
                                    });
                                    this.searchQuery = '';
                                    this.searchResults = [];
                                }
                            } catch (error) {
                                console.error('Error adding participant:', error);
                            }
                        },

                        async removeParticipant(user) {
                            if (!confirm(`Retirer ${user.name} du groupe ?`)) return;

                            try {
                                const response = await fetch(`/conversations/${this.currentConversationId}/participants/${user.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    this.currentConversationParticipants = this.currentConversationParticipants.filter(p => p.id !== user.id);
                                }
                            } catch (error) {
                                console.error('Error removing participant:', error);
                            }
                        },

                        async leaveGroup() {
                            if (!confirm('Voulez-vous vraiment quitter ce groupe ?')) return;

                            try {
                                const response = await fetch(`/conversations/${this.currentConversationId}/leave`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                });

                                if (response.ok) {
                                    this.view = 'list';
                                    this.fetchConversations();
                                }
                            } catch (error) {
                                console.error('Error leaving group:', error);
                            }
                        },

                        async createGroupConversation() {
                            if (!this.newGroupName.trim() || this.selectedParticipants.length === 0) return;

                            try {
                                const participantIds = this.selectedParticipants.map(u => u.id);
                                // Add myself implicitly handled by backend or explicit? Backend handles creator.
                                // But backend expects 'participants' array.
                                // Let's send selected IDs.

                                const response = await fetch('/conversations', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        type: 'group',
                                        name: this.newGroupName,
                                        participants: participantIds
                                    })
                                });

                                if (response.ok) {
                                    const conv = await response.json();
                                    this.selectConversation(conv);
                                    // Reset state
                                    this.newGroupName = '';
                                    this.selectedParticipants = [];
                                    this.searchQuery = '';
                                }
                            } catch (error) {
                                console.error('Error creating group:', error);
                            }
                        },

                        async fetchMessages(url = null) {
                            const isLoadMore = !!url;
                            if (isLoadMore) this.isLoadingMore = true;

                            let fetchUrl = url;
                            if (!fetchUrl) {
                                fetchUrl = this.currentConversationId
                                    ? `/conversations/${this.currentConversationId}`
                                    : '{{ route('messages.fetch') }}';
                            }

                            try {
                                const response = await fetch(fetchUrl);
                                if (response.ok) {
                                    const data = await response.json();
                                    const newMessages = data.data ? data.data.reverse() : data; // Reverse to be chronological

                                    if (isLoadMore) {
                                        // Prepend messages
                                        const container = document.getElementById('chat-bubble-messages');
                                        const oldHeight = container.scrollHeight;
                                        const oldScrollTop = container.scrollTop;

                                        this.messages = [...newMessages, ...this.messages];

                                        this.$nextTick(() => {
                                            // Restore scroll position
                                            const newHeight = container.scrollHeight;
                                            container.scrollTop = newHeight - oldHeight + oldScrollTop;
                                        });
                                    } else {
                                        this.messages = newMessages;
                                        this.scrollToBottom();
                                    }

                                    this.nextPageUrl = data.next_page_url;
                                }
                            } catch (error) {
                                console.error('Error fetching messages:', error);
                            } finally {
                                this.isLoadingMore = false;
                            }
                        },

                        handleScroll(e) {
                            if (e.target.scrollTop < 50 && this.nextPageUrl && !this.isLoadingMore) {
                                this.fetchMessages(this.nextPageUrl);
                            }
                        },

                        async sendMessage() {
                            if (!this.newMessage.trim() && !this.attachmentFile) return;

                            const content = this.newMessage;

                            if (this.editingMessage) {
                                await this.updateMessage(content);
                                return;
                            }

                            const replyToId = this.replyToMessage?.id;
                            const file = this.attachmentFile;

                            // Reset UI immediately
                            this.newMessage = '';
                            this.replyToMessage = null;
                            this.clearAttachment();

                            try {
                                const formData = new FormData();
                                formData.append('content', content);
                                if (this.currentConversationId) {
                                    formData.append('conversation_id', this.currentConversationId);
                                }
                                if (replyToId) {
                                    formData.append('reply_to_message_id', replyToId);
                                }
                                if (file) {
                                    formData.append('attachment', file);
                                }

                                const response = await fetch('{{ route('messages.store') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'X-Requested-With': 'XMLHttpRequest'
                                        // Content-Type not set to let browser set boundary for FormData
                                    },
                                    body: formData
                                });

                                if (response.ok) {
                                    const msg = await response.json();
                                    this.messages.push(msg);
                                    this.scrollToBottom();
                                    this.fetchConversations();
                                }
                            } catch (error) {
                                console.error('Error sending message:', error);
                            }
                        },

                        handleFileSelect(e) {
                            const file = e.target.files[0];
                            if (!file) return;

                            this.attachmentFile = file;
                            this.attachmentName = file.name;

                            if (file.type.startsWith('image/')) {
                                this.attachmentType = 'image';
                                const reader = new FileReader();
                                reader.onload = (e) => { this.attachmentPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            } else if (file.type.startsWith('audio/')) {
                                this.attachmentType = 'audio';
                                this.attachmentPreview = true; // Placeholder
                            } else {
                                this.attachmentType = 'file';
                                this.attachmentPreview = true; // Placeholder
                            }
                        },

                        clearAttachment() {
                            this.attachmentFile = null;
                            this.attachmentPreview = null;
                            this.attachmentType = null;
                            this.attachmentName = '';
                            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                        },

                        async startRecording() {
                            try {
                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                this.mediaRecorder = new MediaRecorder(stream);
                                this.audioChunks = [];
                                this.isRecording = true;

                                this.mediaRecorder.ondataavailable = (e) => {
                                    this.audioChunks.push(e.data);
                                };

                                this.mediaRecorder.onstop = () => {
                                    const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                                    const audioFile = new File([audioBlob], "voice_message.webm", { type: 'audio/webm' });

                                    this.attachmentFile = audioFile;
                                    this.attachmentType = 'audio';
                                    this.attachmentName = 'Message vocal';
                                    this.attachmentPreview = true;
                                    this.isRecording = false;

                                    // Stop all tracks
                                    stream.getTracks().forEach(track => track.stop());
                                };

                                this.mediaRecorder.start();
                            } catch (err) {
                                console.error('Error accessing microphone:', err);
                                alert('Impossible d\'accéder au microphone.');
                            }
                        },

                        stopRecording() {
                            if (this.mediaRecorder && this.isRecording) {
                                this.mediaRecorder.stop();
                            }
                        },

                        async updateMessage(content) {
                            const messageId = this.editingMessage.id;
                            this.newMessage = '';
                            this.editingMessage = null;

                            try {
                                const response = await fetch(`/messages/${messageId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ content })
                                });

                                if (response.ok) {
                                    const updatedMsg = await response.json();
                                    const index = this.messages.findIndex(m => m.id === messageId);
                                    if (index !== -1) {
                                        this.messages[index].content = updatedMsg.content;
                                        this.messages[index].edited_at = updatedMsg.edited_at;
                                    }
                                }
                            } catch (error) {
                                console.error('Error updating message:', error);
                            }
                        },

                        handleReply(messageId) {
                            const msg = this.messages.find(m => m.id === messageId);
                            if (msg) {
                                this.replyToMessage = msg;
                                this.editingMessage = null;
                                this.contextMenu.show = false;
                                this.$nextTick(() => {
                                    this.$el.querySelector('textarea').focus();
                                });
                            }
                        },

                        handleEdit(messageId) {
                            const msg = this.messages.find(m => m.id === messageId);
                            if (msg) {
                                this.editingMessage = msg;
                                this.replyToMessage = null;
                                this.newMessage = msg.content;
                                this.contextMenu.show = false;
                                this.$nextTick(() => {
                                    this.$el.querySelector('textarea').focus();
                                });
                            }
                        },

                        cancelEdit() {
                            this.replyToMessage = null;
                            this.editingMessage = null;
                            this.newMessage = '';
                        },

                        showContextMenu(e, messageId) {
                            this.contextMenu.messageId = messageId;
                            this.contextMenu.show = true;

                            this.$nextTick(() => {
                                const chatContainer = document.getElementById('chat-bubble-messages');
                                const chatRect = chatContainer.getBoundingClientRect();

                                let x = e.clientX - chatRect.left;
                                let y = e.clientY - chatRect.top;

                                const menuWidth = 256;
                                const menuHeight = 220;

                                if (x + menuWidth > chatRect.width) x = chatRect.width - menuWidth - 10;
                                if (y + menuHeight > chatRect.height) y = y - menuHeight;

                                this.contextMenu.x = Math.max(10, x);
                                this.contextMenu.y = Math.max(10, y);
                            });
                        },

                        startLongPress(e, messageId) {
                            this.longPressTimer = setTimeout(() => {
                                const touch = e.touches[0];
                                this.showContextMenu({ clientX: touch.clientX, clientY: touch.clientY }, messageId);
                            }, 600);
                        },

                        clearLongPress() {
                            clearTimeout(this.longPressTimer);
                        },

                        isMyMessage(messageId) {
                            const msg = this.messages.find(m => m.id === messageId);
                            return msg && msg.sender_id == {{ Auth::id() ?? 'null' }};
                        },

                        isEditable(messageId) {
                            const msg = this.messages.find(m => m.id === messageId);
                            if (!msg) return false;
                            const created = new Date(msg.created_at);
                            const now = new Date();
                            const diffMinutes = (now - created) / 60000;
                            return diffMinutes <= 15;
                        },

                        hasReacted(messageId, emoji) {
                            const msg = this.messages.find(m => m.id === messageId);
                            if (!msg || !msg.reactions_grouped || !msg.reactions_grouped[emoji]) return false;
                            return msg.reactions_grouped[emoji].reacted_by_me;
                        },

                        async reactToMessage(messageId, emoji) {
                            this.contextMenu.show = false;
                            try {
                                const response = await fetch(`/messages/${messageId}/react`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ emoji })
                                });
                                const data = await response.json();
                                const msg = this.messages.find(m => m.id === messageId);
                                if (msg) {
                                    msg.reactions_grouped = data.reactions;
                                }
                            } catch (error) {
                                console.error('Error reacting to message:', error);
                            }
                        },

                        async reportMessage(messageId) {
                            this.contextMenu.show = false;
                            const reason = prompt('Raison du signalement (optionnel) :');
                            if (reason === null) return; // Cancelled

                            try {
                                const response = await fetch(`/messages/${messageId}/report`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ reason })
                                });

                                if (response.ok) {
                                    alert('Message signalé aux administrateurs.');
                                }
                            } catch (error) {
                                console.error('Error reporting message:', error);
                            }
                        },

                        async deleteMessage(messageId, mode) {
                            this.contextMenu.show = false;
                            if (mode === 'all') {
                                if (!confirm('Retirer ce message pour tout le monde ?')) return;
                                try {
                                    const response = await fetch(`/messages/${messageId}`, {
                                        method: 'DELETE',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                    });
                                    if (response.ok) {
                                        const msg = this.messages.find(m => m.id === messageId);
                                        if (msg) {
                                            msg.is_deleted = true;
                                            msg.content = 'Ce message a été supprimé';
                                        }
                                    }
                                } catch (error) { console.error('Error deleting message:', error); }
                            } else if (mode === 'me') {
                                try {
                                    const response = await fetch(`/messages/${messageId}/hide`, {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                    });
                                    if (response.ok) {
                                        this.messages = this.messages.filter(m => m.id !== messageId);
                                    }
                                } catch (error) { console.error('Error hiding message:', error); }
                            }
                        },

                        scrollToBottom() {
                            this.$nextTick(() => {
                                const container = document.getElementById('chat-bubble-messages');
                                if (container) container.scrollTop = container.scrollHeight;
                            });
                        },

                        formatTime(dateStr) {
                            const date = new Date(dateStr);
                            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        },

                        formatDate(dateStr) {
                            const date = new Date(dateStr);
                            const now = new Date();
                            if (date.toDateString() === now.toDateString()) {
                                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            }
                            return date.toLocaleDateString();
                        },

                        showHeartAnimation(e) {
                            const heart = document.createElement('div');
                            heart.innerHTML = '<i class="fas fa-heart text-rose-500 text-4xl drop-shadow-lg"></i>';
                            heart.style.position = 'fixed';
                            heart.style.left = `${e.clientX - 20}px`;
                            heart.style.top = `${e.clientY - 20}px`;
                            heart.style.zIndex = '9999';
                            heart.style.pointerEvents = 'none';
                            heart.style.animation = 'float-up 1s ease-out forwards';

                            document.body.appendChild(heart);

                            setTimeout(() => {
                                heart.remove();
                            }, 1000);
                        }
                    }
                }
            </script>

            <style>
                [x-cloak] {
                    display: none !important;
                }

                .custom-scrollbar::-webkit-scrollbar {
                    width: 3px;
                }

                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 10px;
                }

                @keyframes float-up {
                    0% {
                        transform: scale(0.5) translateY(0);
                        opacity: 1;
                    }

                    50% {
                        transform: scale(1.2) translateY(-20px);
                        opacity: 1;
                    }

                    100% {
                        transform: scale(1) translateY(-50px);
                        opacity: 0;
                    }
                }
            </style>
@endauth