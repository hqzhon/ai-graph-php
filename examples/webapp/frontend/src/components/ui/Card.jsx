import React from 'react';

const Card = ({ 
  children, 
  variant = 'default', 
  padding = 'md', 
  shadow = 'soft',
  hover = false,
  interactive = false,
  glass = false,
  gradient = false,
  className = '', 
  ...props 
}) => {
  const baseClasses = `
    rounded-lg sm:rounded-xl lg:rounded-2xl border transition-all duration-300 ease-smooth relative overflow-hidden
    ${interactive ? 'cursor-pointer' : ''}
  `;
  
  const variants = {
    default: 'bg-white border-neutral-200',
    primary: 'border-primary-200 bg-gradient-to-br from-primary-50 to-primary-100',
    secondary: 'border-secondary-200 bg-gradient-to-br from-secondary-50 to-secondary-100',
    success: 'border-success-200 bg-gradient-to-br from-success-50 to-success-100',
    warning: 'border-warning-200 bg-gradient-to-br from-warning-50 to-warning-100',
    danger: 'border-error-200 bg-gradient-to-br from-error-50 to-error-100',
    dark: 'bg-gradient-to-br from-neutral-800 to-neutral-900 border-neutral-700 text-white',
    glass: 'glass-effect border-white/20 text-neutral-900',
    gradient: 'bg-gradient-to-br from-primary-500 via-accent-500 to-secondary-500 text-white border-0',
  };
  
  const paddings = {
    none: '',
    xs: 'p-3',
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8',
    xl: 'p-10',
  };
  
  const shadows = {
    none: '',
    soft: 'shadow-soft',
    medium: 'shadow-medium',
    strong: 'shadow-strong',
    glow: 'shadow-glow',
  };
  
  const hoverEffects = {
    lift: 'hover:shadow-strong hover:-translate-y-2 hover:scale-[1.02]',
    glow: 'hover:shadow-glow',
    scale: 'hover:scale-[1.05]',
    none: '',
  };
  
  const getVariant = () => {
    if (glass) return variants.glass;
    if (gradient) return variants.gradient;
    return variants[variant];
  };
  
  const getHoverEffect = () => {
    if (!hover && !interactive) return '';
    if (hover === true || interactive) return hoverEffects.lift;
    return hoverEffects[hover] || '';
  };

  return (
    <div
      className={`${baseClasses} ${getVariant()} ${paddings[padding]} ${shadows[shadow]} ${getHoverEffect()} ${className}`}
      {...props}
    >
      {children}
    </div>
  );
};

// Card sub-components with enhanced styling
Card.Header = ({ children, className = '', divider = true, ...props }) => (
  <div 
    className={`
      flex flex-col space-y-1.5 p-4 sm:p-6
      ${divider ? 'border-b border-neutral-200/50 pb-4 mb-6' : 'mb-4'} 
      ${className}
    `} 
    {...props}
  >
    {children}
  </div>
);

Card.Body = ({ children, className = '', ...props }) => (
  <div className={`flex-1 ${className}`} {...props}>
    {children}
  </div>
);

Card.Footer = ({ children, className = '', divider = true, ...props }) => (
  <div 
    className={`
      ${divider ? 'border-t border-neutral-200/50 pt-4 mt-6' : 'mt-4'} 
      ${className}
    `} 
    {...props}
  >
    {children}
  </div>
);

Card.Content = ({ children, className = '', ...props }) => (
  <div className={`p-4 sm:p-6 pt-0 ${className}`} {...props}>
    {children}
  </div>
);

Card.Title = ({ children, className = '', size = 'lg', ...props }) => {
  const sizes = {
    sm: 'text-sm sm:text-base font-semibold',
    md: 'text-base sm:text-lg font-semibold',
    lg: 'text-lg sm:text-xl font-bold',
    xl: 'text-xl sm:text-2xl font-bold',
  };
  
  return (
    <h3 className={`${sizes[size]} text-neutral-900 leading-tight ${className}`} {...props}>
      {children}
    </h3>
  );
};

Card.Description = ({ children, className = '', muted = true, ...props }) => (
  <p 
    className={`
      text-sm leading-relaxed 
      ${muted ? 'text-secondary-600' : 'text-secondary-700'} 
      ${className}
    `} 
    {...props}
  >
    {children}
  </p>
);

Card.Image = ({ src, alt, className = '', aspectRatio = 'video', ...props }) => {
  const aspectRatios = {
    square: 'aspect-square',
    video: 'aspect-video',
    portrait: 'aspect-[3/4]',
    wide: 'aspect-[21/9]',
  };
  
  return (
    <div className={`${aspectRatios[aspectRatio]} overflow-hidden rounded-xl ${className}`}>
      <img 
        src={src} 
        alt={alt} 
        className="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
        {...props}
      />
    </div>
  );
};

Card.Badge = ({ children, variant = 'primary', className = '', ...props }) => {
  const variants = {
    primary: 'bg-primary-100 text-primary-800 border-primary-200',
    secondary: 'bg-secondary-100 text-secondary-800 border-secondary-200',
    success: 'bg-success-100 text-success-800 border-success-200',
    warning: 'bg-warning-100 text-warning-800 border-warning-200',
    danger: 'bg-error-100 text-error-800 border-error-200',
  };
  
  return (
    <span 
      className={`
        inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
        border ${variants[variant]} ${className}
      `}
      {...props}
    >
      {children}
    </span>
  );
};

// Compound card variants
Card.Feature = ({ icon, title, description, className = '', ...props }) => (
  <Card variant="default" hover="lift" padding="lg" className={`text-center ${className}`} {...props}>
    {icon && (
      <div className="w-12 h-12 mx-auto mb-4 flex items-center justify-center rounded-xl bg-primary-100 text-primary-600">
        {icon}
      </div>
    )}
    <Card.Title size="md" className="mb-2">{title}</Card.Title>
    <Card.Description>{description}</Card.Description>
  </Card>
);

Card.Stat = ({ label, value, change, trend, className = '', ...props }) => (
  <Card variant="default" padding="md" className={className} {...props}>
    <div className="flex items-center justify-between">
      <div>
        <Card.Description className="mb-1">{label}</Card.Description>
        <Card.Title size="xl">{value}</Card.Title>
      </div>
      {change && (
        <div className={`text-sm font-medium ${trend === 'up' ? 'text-success-600' : 'text-error-600'}`}>
          {change}
        </div>
      )}
    </div>
  </Card>
);

export default Card;