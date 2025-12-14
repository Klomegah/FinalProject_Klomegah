<?php header('Content-Type: text/css'); ?>

/* User Dropdown Styles - Add this to your existing navbar CSS */

.user-dropdown {
    position: relative;
}

.user-menu-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: transparent;
    border: 2px solid var(--border-light);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.user-menu-btn:hover {
    border-color: var(--primary-purple);
    background-color: var(--input-color);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.user-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.95rem;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dropdown-arrow {
    color: var(--text-medium);
    transition: transform 0.3s ease;
    flex-shrink: 0;
}

.user-dropdown-menu.show .dropdown-arrow {
    transform: rotate(180deg);
}

.user-dropdown-menu {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    overflow: hidden;
}

.user-dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--bg-off-white);
}

.user-avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-full-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.user-email {
    font-size: 0.85rem;
    color: var(--text-medium);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-light);
    margin: 0.5rem 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--text-dark);
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    font-weight: 500;
}

.dropdown-item:hover {
    background-color: var(--bg-off-white);
    color: var(--primary-purple);
}

.dropdown-item svg {
    color: var(--text-medium);
    flex-shrink: 0;
}

.dropdown-item:hover svg {
    color: var(--primary-purple);
}

.logout-item {
    color: #EF4444;
}

.logout-item:hover {
    background-color: #FEF2F2;
    color: #DC2626;
}

.logout-item svg {
    color: #EF4444;
}

.logout-item:hover svg {
    color: #DC2626;
}

/* Fallback logout button */
.logout-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    background-color: var(--primary-purple);
    color: white;
    text-transform: uppercase;
}

.logout-btn:hover {
    background-color: var(--primary-purple-dark);
    transform: translateY(-2px);
}


@media (max-width: 768px) {
    .user-name {
        display: none;
    }

    .user-dropdown-menu {
        right: 0;
        left: auto;
        min-width: 220px;
    }

    .back-btn span {
        display: none;
    }
    .back-btn {
        padding: 0.5rem;
    }
}
