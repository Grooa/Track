import React from 'react';
import PropTypes from 'prop-types';
import ReactMarkdown from 'react-markdown';

import CourseApi from './courseApi';

export default class ViewCourse extends React.Component {
  constructor() {
    super();

    this.state = {
      loaded: false,
      course: {},
    };

    this.hasLoaded = this.hasLoaded.bind(this);
    this.hasCourse = this.hasCourse.bind(this);
  }

  componentDidMount() {
    CourseApi.findByLabel(this.props.courseLabel)
      .then(course => this.setState({ course, loaded: true }))
      .catch(console.error);
  }

  hasLoaded() {
    return this.state.loaded;
  }

  hasCourse() {
    return this.state.course !== {};
  }

  render() {
    if (!this.hasLoaded()) {
      return <div>
        <p>Loading course...</p>
      </div>;
    }

    if (this.hasLoaded() && !this.hasCourse()) {
      return <div className="feedback feedback-error">
        <p>Could not find any course with label: {this.props.courseLabel}</p>
      </div>;
    }

    return <article>
      <header className="course-header">
        <div className="cover-image" style={{ backgroundImage: `url(${this.state.course.cover})` }} />
        <h1 className="centered">{this.state.course.name}</h1>
      </header>

      <div className="course-metadata columns float-top spaced">

        <section className="modules-section">
          <h2>Modules</h2>

          {this.state.course.modules.length > 0 && <ul className="course-list">
            {this.state.course.modules.map(m => <li key={`module-${m.id}`} className="course-module clickable">
              <a href={m.url}>
                <div className="course-module-metadata">
                  <strong className="type">{m.type} {m.num}</strong>
                  <h3 className="title">{m.title}</h3>
                </div>

                <ReactMarkdown className="description" source={m.shortDescription}/>

                <button>Checkout</button>
              </a>
            </li>)}
          </ul>}
        </section>


        <ReactMarkdown source={this.state.course.introduction} className="course-description" />
      </div>
    </article>;
  }
}

ViewCourse.propTypes = {
  courseLabel: PropTypes.string.isRequired,
};
