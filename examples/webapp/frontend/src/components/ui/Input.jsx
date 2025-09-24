import React, { forwardRef, useState } from 'react';

const Input = forwardRef(({ 
  type = 'text',
  label = '',
  placeholder = '',
  error = '',
  success = '',
  hint = '',
  icon = null,
  iconPosition = 'left',
  size = 'md',
  variant = 'default',
  disabled = false,
  required = false,
  fullWidth = false,
  className = '',
  ...props 
}, ref) => {
  const [focused, setFocused] = useState(false);
  
  const baseClasses = `
    w-full rounded-xl border transition-all duration-200 
    focus:outline-none focus:ring-2 focus:ring-offset-1
    disabled:opacity-50 disabled:cursor-not-allowed
    ${fullWidth ? 'w-full' : ''}
  `;
  
  const variants = {
    default: `
      border-gray-300 bg-white text-gray-900 
      focus:border-blue-500 focus:ring-blue-500/20
      placeholder:text-gray-400
    `,
    filled: `
      border-transparent bg-gray-100 text-gray-900 
      focus:bg-white focus:border-blue-500 focus:ring-blue-500/20
      placeholder:text-gray-400
    `,
    glass: `
      glass-effect border-white/20 text-gray-900 
      focus:border-blue-500 focus:ring-blue-500/20
      placeholder:text-gray-500
    `,
  };
  
  const sizes = {
    sm: 'px-3 py-2 text-sm',
    md: 'px-4 py-2.5 text-sm',
    lg: 'px-4 py-3 text-base',
    xl: 'px-5 py-4 text-lg',
  };
  
  const iconSizes = {
    sm: 'w-4 h-4',
    md: 'w-5 h-5',
    lg: 'w-5 h-5',
    xl: 'w-6 h-6',
  };
  
  const getBorderColor = () => {
    if (error) return 'border-red-500 focus:border-red-500 focus:ring-red-500/20';
    if (success) return 'border-green-500 focus:border-green-500 focus:ring-green-500/20';
    return '';
  };
  
  const getIconPadding = () => {
    if (!icon) return '';
    const paddingMap = {
      sm: iconPosition === 'left' ? 'pl-10' : 'pr-10',
      md: iconPosition === 'left' ? 'pl-11' : 'pr-11',
      lg: iconPosition === 'left' ? 'pl-12' : 'pr-12',
      xl: iconPosition === 'left' ? 'pl-14' : 'pr-14',
    };
    return paddingMap[size];
  };
  
  const getIconPosition = () => {
    const positionMap = {
      sm: iconPosition === 'left' ? 'left-3' : 'right-3',
      md: iconPosition === 'left' ? 'left-3' : 'right-3',
      lg: iconPosition === 'left' ? 'left-3' : 'right-3',
      xl: iconPosition === 'left' ? 'left-4' : 'right-4',
    };
    return positionMap[size];
  };

  return (
    <div className={`${fullWidth ? 'w-full' : ''}`}>
      {label && (
        <label className="block text-sm font-medium text-gray-700 mb-2">
          {label}
          {required && <span className="text-red-500 ml-1">*</span>}
        </label>
      )}
      
      <div className="relative">
        <input
          ref={ref}
          type={type}
          placeholder={placeholder}
          disabled={disabled}
          required={required}
          className={`
            ${baseClasses} 
            ${variants[variant]} 
            ${sizes[size]} 
            ${getBorderColor()}
            ${getIconPadding()}
            ${className}
          `}
          onFocus={() => setFocused(true)}
          onBlur={() => setFocused(false)}
          {...props}
        />
        
        {icon && (
          <div className={`
            absolute top-1/2 transform -translate-y-1/2 ${getIconPosition()}
            ${iconSizes[size]} text-gray-400 pointer-events-none
            ${focused ? 'text-blue-500' : ''}
            ${error ? 'text-red-500' : ''}
            ${success ? 'text-green-500' : ''}
          `}>
            {icon}
          </div>
        )}
      </div>
      
      {(error || success || hint) && (
        <div className="mt-2">
          {error && (
            <p className="text-sm text-red-600 flex items-center gap-1">
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
              </svg>
              {error}
            </p>
          )}
          
          {success && (
            <p className="text-sm text-green-600 flex items-center gap-1">
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
              </svg>
              {success}
            </p>
          )}
          
          {hint && !error && !success && (
            <p className="text-sm text-gray-500">{hint}</p>
          )}
        </div>
      )}
    </div>
  );
});

Input.displayName = 'Input';

// Input variants for common use cases
Input.Search = forwardRef((props, ref) => (
  <Input
    ref={ref}
    type="search"
    icon={
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    }
    placeholder="Search..."
    {...props}
  />
));

Input.Email = forwardRef((props, ref) => (
  <Input
    ref={ref}
    type="email"
    icon={
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
      </svg>
    }
    placeholder="Enter your email"
    {...props}
  />
));

Input.Password = forwardRef(({ showPassword, onTogglePassword, ...props }, ref) => (
  <Input
    ref={ref}
    type={showPassword ? 'text' : 'password'}
    icon={
      <button
        type="button"
        onClick={onTogglePassword}
        className="pointer-events-auto hover:text-gray-600"
      >
        {showPassword ? (
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
          </svg>
        ) : (
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        )}
      </button>
    }
    iconPosition="right"
    placeholder="Enter your password"
    {...props}
  />
));

export default Input;