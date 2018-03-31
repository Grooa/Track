import PropTypes from 'prop-types';

import ReactMarkdown from 'react-markdown';

const CommonCard = (props) => {
  const {
    link = null,
    title = null,
    type = null,
    description = null,
    buttonLabel = null,
    isDisabled = false,
  } = props;

  const typeRendered = type ? <strong className="type">{type}</strong> : '';
  const titleRendered = title ? <h3 className="title">{title}</h3> : '';
  let buttonRendered = buttonLabel ? <button>{buttonLabel}</button> : <button>Checkout</button>;

  if (isDisabled && !buttonLabel) {
    buttonRendered = <button>You don't have acccess</button>;
  }

  const header = <div className="course-module-metadata">
    {typeRendered}
    {titleRendered}
  </div>;

  const body = description
    ? <ReactMarkdown className="description" source={description} />
    : '';

  let cssClasses = 'course-module';
  if (link && !isDisabled) {
    cssClasses += ' clickable';
  } else if (isDisabled) {
    cssClasses += ' inactive';
  }

  if (!link) {
    return <div className={cssClasses}>
      {header}

      {body}

      {buttonRendered}
    </div>;
  }

  return <a href={link} className={cssClasses}>
    {header}

    {body}

    {buttonRendered}
  </a>;
};

CommonCard.propTypes = {
  link: PropTypes.string,
  title: PropTypes.string,
  type: PropTypes.string,
  description: PropTypes.string,
  buttonLabel: PropTypes.string,
  isDisabled: PropTypes.bool,
};

export default CommonCard;
