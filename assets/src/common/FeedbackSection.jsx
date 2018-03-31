import PropTypes from 'prop-types';

const FeedbackSection = (props) => {
  const {
    title = null,
    text = null,
    link = null,
    type = null,
  } = props;

  let cssClasses = 'feedback';
  if (type) {
    cssClasses += ` feedback-${type}`;
  }

  const textRendered = text ? <p>{text}</p> : '';
  const linkRendered = link ? <a href={link.url} className="button pill">{link.label}</a> : '';

  if (!title) {
    return <div className={cssClasses}>
      {textRendered}

      {linkRendered}
    </div>;
  }

  return <section className={cssClasses}>
    <h2>{title}</h2>

    {textRendered}

    {linkRendered}
  </section>;
};

FeedbackSection.propTypes = {
  title: PropTypes.string,
  text: PropTypes.string,
  link: PropTypes.object,
  type: PropTypes.string,
};

export default FeedbackSection;
